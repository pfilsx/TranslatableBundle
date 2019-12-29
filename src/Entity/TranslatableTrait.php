<?php


namespace Pfilsx\Translatable\Entity;


use Pfilsx\Translatable\Contract\TranslationInterface;
use Pfilsx\Translatable\Exception\UndefinedMethodException;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;

trait TranslatableTrait
{
    /**
     * @var ArrayCollection
     */
    private $translations;

    private $defaultLocale;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getDefaultLocale(): ?string
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale(string $locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function getTranslations()
    {
        return $this->translations ?? ($this->translations = new ArrayCollection());
    }

    public function setTranslations(iterable $translations)
    {
        foreach ($translations as $translation) {
            $this->addTranslation($translation);
        }
    }

    public function addTranslation(TranslationInterface $translation): void
    {
        $this->getTranslations()->set($translation->getLocale(), $translation);
        $translation->setTranslatable($this);
    }

    public function removeTranslation(TranslationInterface $translation): void
    {
        $this->getTranslations()->removeElement($translation);
    }

    public function translate(?string $locale = null): ?TranslationInterface
    {
        if ($locale === null) {
            $locale = $this->getDefaultLocale();
        }
        return $this->getTranslations()->containsKey($locale)
            ? $this->getTranslations()->get($locale)
            : null;
    }

    public function __call(string $name, array $arguments)
    {
        $prefix = strtolower(substr($name, 0, 3));
        $locale = array_shift($arguments);
        if ($prefix === 'get') {
            $translation = $this->translate($locale);
            if ($translation !== null && method_exists($translation, $name)) {
                return $translation->$name(...$arguments);
            }
            $reflection = new ReflectionClass(static::getTranslationEntityClass());
            if ($reflection->hasMethod($name)) {
                return null;
            }
        } elseif ($prefix === 'set') {
            $translation = $this->translate($locale);
            if ($translation !== null && method_exists($translation, $name)) {
                $translation->$name(...$arguments);
                return $this;
            } elseif ($translation === null) {
                $translationClass = static::getTranslationEntityClass();
                if (method_exists($translationClass, $name)){
                    /**
                     * @var TranslationInterface $translation
                     */
                    $translation = new $translationClass();
                    $translation
                        ->setLocale($locale === null ? $this->defaultLocale : $locale)
                        ->$name(...$arguments);
                    $this->addTranslation($translation);
                    return $this;
                }
            }
        }
        $className = static::class;
        throw new UndefinedMethodException("Attempted to call an undefined method named \"$name\" of class \"$className\".");
    }

    public static function getTranslationEntityClass(): string
    {
        $fqnParts = explode('\\', static::class);
        $className = array_pop($fqnParts);
        $fqnParts[] = 'Translation';
        $fqnParts[] = $className . 'Translation';

        return implode('\\', $fqnParts);
    }
}
