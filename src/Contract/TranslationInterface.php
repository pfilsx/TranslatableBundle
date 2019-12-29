<?php


namespace Pfilsx\Translatable\Contract;


interface TranslationInterface
{
    public function getLocale(): ?string;

    public function setLocale(string $locale): self;

    public function setTranslatable(TranslatableInterface $translatable): self;

    public function getTranslatable(): ?TranslatableInterface;

    public static function getTranslatableEntityClass(): string;
}