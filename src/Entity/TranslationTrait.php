<?php


namespace Pfilsx\Translatable\Entity;


use Pfilsx\Translatable\Contract\TranslatableInterface;
use Pfilsx\Translatable\Contract\TranslationInterface;

trait TranslationTrait
{
    protected $locale;
    protected $translatable;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): TranslationInterface
    {
        $this->locale = $locale;
        return $this;
    }

    public function setTranslatable(TranslatableInterface $translatable): TranslationInterface
    {
        $this->translatable = $translatable;
        return $this;
    }

    public function getTranslatable(): ?TranslatableInterface
    {
        return $this->translatable;
    }

    public static function getTranslatableEntityClass(): string
    {
        $fqnParts = explode('\\', static::class);
        $className = array_pop($fqnParts);
        array_pop($fqnParts);
        $fqnParts[] = substr($className, 0, -11);
        return implode('\\', $fqnParts);
    }
}