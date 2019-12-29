<?php


namespace Pfilsx\Translatable\EventSubscriber;


use Pfilsx\Translatable\Contract\TranslatableInterface;
use Pfilsx\Translatable\Contract\TranslationInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TranslatableSubscriber implements EventSubscriber
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Request|null
     */
    private $request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $loadClassMetadataEventArgs): void
    {
        $classMetadata = $loadClassMetadataEventArgs->getClassMetadata();
        if ($classMetadata->isMappedSuperclass) {
            return;
        }
        if ($this->isTranslatable($classMetadata)) {
            $this->mapTranslatable($classMetadata);
        }
        if ($this->isTranslation($classMetadata)) {
            $this->mapTranslation($classMetadata);
        }
    }

    public function postLoad(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->setLocales($lifecycleEventArgs);
    }

    public function prePersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->setLocales($lifecycleEventArgs);
    }

    public function getSubscribedEvents(): array
    {
        return [Events::loadClassMetadata, Events::postLoad, Events::prePersist];
    }

    private function isTranslatable(ClassMetadataInfo $classMetadataInfo): bool
    {
        return is_a($classMetadataInfo->getName(), TranslatableInterface::class, true);
    }

    private function mapTranslatable(ClassMetadataInfo $classMetadataInfo): void
    {
        if ($classMetadataInfo->hasAssociation('translations')) {
            return;
        }
        $classMetadataInfo->mapOneToMany([
            'fieldName' => 'translations',
            'mappedBy' => 'translatable',
            'indexBy' => 'locale',
            'cascade' => ['persist', 'merge', 'remove'],
            'fetch' => ClassMetadataInfo::FETCH_LAZY,
            'targetEntity' => $this->getTranslationClassName($classMetadataInfo),
            'orphanRemoval' => true,
        ]);
    }

    private function isTranslation(ClassMetadataInfo $classMetadataInfo): bool
    {
        return is_a($classMetadataInfo->getName(), TranslationInterface::class, true);
    }

    private function mapTranslation(ClassMetadataInfo $classMetadataInfo): void
    {
        if (!$classMetadataInfo->hasAssociation('translatable')) {
            $classMetadataInfo->mapManyToOne([
                'fieldName' => 'translatable',
                'inversedBy' => 'translations',
                'cascade' => ['persist', 'merge'],
                'fetch' => ClassMetadataInfo::FETCH_LAZY,
                'joinColumns' => [[
                    'name' => 'translatable_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ]],
                'targetEntity' => $this->getTranslatableClassName($classMetadataInfo),
            ]);
        }
        $name = $classMetadataInfo->getTableName() . '_unique_translation';
        if (!$this->hasUniqueTranslationConstraint($classMetadataInfo, $name)) {
            $classMetadataInfo->table['uniqueConstraints'][$name] = [
                'columns' => ['translatable_id', 'locale'],
            ];
        }
        if (!$classMetadataInfo->hasField('locale') && !$classMetadataInfo->hasAssociation('locale')) {
            $classMetadataInfo->mapField([
                'fieldName' => 'locale',
                'type' => 'string',
                'length' => 5,
            ]);
        }
    }

    private function hasUniqueTranslationConstraint(ClassMetadataInfo $classMetadataInfo, string $name): bool
    {
        if (!isset($classMetadataInfo->table['uniqueConstraints'])) {
            return false;
        }
        return isset($classMetadataInfo->table['uniqueConstraints'][$name]);
    }

    private function setLocales(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $entity = $lifecycleEventArgs->getEntity();
        if (!$entity instanceof TranslatableInterface) {
            return;
        }
        if ($this->request) {
            $currentLocale = $this->request->getLocale();
            if ($currentLocale) {
                $entity->setDefaultLocale($currentLocale);
            }
        }
    }

    private function getTranslationClassName(ClassMetadataInfo $classMetadataInfo): string
    {
        $refl = $classMetadataInfo->getReflectionClass();
        if ($refl) {
            return $classMetadataInfo->getReflectionClass()->getMethod(
                'getTranslationEntityClass'
            )->invoke(null);
        } else {
            $fqnParts = explode('\\', $classMetadataInfo->getName());
            $className = array_pop($fqnParts);
            $fqnParts[] = 'Translation';
            $fqnParts[] = $className . 'Translation';

            return implode('\\', $fqnParts);
        }
    }

    private function getTranslatableClassName(ClassMetadataInfo $classMetadataInfo): string
    {
        $refl = $classMetadataInfo->getReflectionClass();
        if ($refl) {
            return $classMetadataInfo->getReflectionClass()->getMethod(
                'getTranslatableEntityClass'
            )->invoke(null);
        } else {
            $fqnParts = explode('\\', $classMetadataInfo->getName());
            $className = array_pop($fqnParts);
            array_pop($fqnParts);
            $fqnParts[] = substr($className, 0, -11);
            return implode('\\', $fqnParts);
        }
    }
}