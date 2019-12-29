<?php


namespace Pfilsx\Translatable\Contract;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\RequestStack;

interface TranslatableInterface
{
    public function getDefaultLocale(): ?string;

    public function setDefaultLocale(string $locale);
    /**
     * @return Collection|TranslationInterface[]
     */
    public function getTranslations();

    public function addTranslation(TranslationInterface $translation): void;
    public function removeTranslation(TranslationInterface $translation): void;

    public function translate(?string $locale = null): ?TranslationInterface;

    public static function getTranslationEntityClass(): string;
}