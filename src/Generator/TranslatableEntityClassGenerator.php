<?php


namespace Pfilsx\Translatable\Generator;


use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

class TranslatableEntityClassGenerator
{
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function generateEntityClass(ClassNameDetails $entityClassDetails, bool $apiResource, bool $withPasswordUpgrade = false): string
    {
        $repoClassDetails = $this->generator->createClassNameDetails(
            $entityClassDetails->getRelativeName(),
            'Repository\\',
            'Repository'
        );
        $entityPath = $this->generator->generateClass(
            $entityClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/Entity.tpl.php',
            [
                'repository_full_class_name' => $repoClassDetails->getFullName(),
                'api_resource' => $apiResource,
            ]
        );
        $entityAlias = strtolower($entityClassDetails->getShortName()[0]);
        $this->generator->generateClass(
            $repoClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/Repository.tpl.php',
            [
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
                'entity_alias' => $entityAlias,
                'with_password_upgrade' => $withPasswordUpgrade,
            ]
        );
        return $entityPath;
    }

    public function generateTranslationClass(ClassNameDetails $translationClassDetails, bool $apiResource, bool $withPasswordUpgrade = false): string
    {
        $repoClassDetails = $this->generator->createClassNameDetails(
            $translationClassDetails->getRelativeName(),
            'Repository\\',
            'Repository'
        );
        $entityPath = $this->generator->generateClass(
            $translationClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/Translation.tpl.php',
            [
                'repository_full_class_name' => $repoClassDetails->getFullName(),
                'api_resource' => $apiResource,
            ]
        );
        $entityAlias = strtolower($translationClassDetails->getShortName()[0]);
        $this->generator->generateClass(
            $repoClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/Repository.tpl.php',
            [
                'entity_full_class_name' => $translationClassDetails->getFullName(),
                'entity_class_name' => $translationClassDetails->getShortName(),
                'entity_alias' => $entityAlias,
                'with_password_upgrade' => $withPasswordUpgrade,
            ]
        );
        return $entityPath;
    }
}