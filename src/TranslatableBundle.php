<?php


namespace Pfilsx\Translatable;


use Pfilsx\Translatable\Contract\TranslatableInterface;
use Pfilsx\Translatable\Contract\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TranslatableBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerForAutoconfiguration(TranslatableInterface::class)->addTag('translatable.translatable');
        $container->registerForAutoconfiguration(TranslationInterface::class)->addTag('translatable.translation');
    }
}