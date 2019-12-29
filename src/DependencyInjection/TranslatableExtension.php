<?php


namespace Pfilsx\Translatable\DependencyInjection;


use Doctrine\Common\EventSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TranslatableExtension extends Extension
{
    private const DOCTRINE_EVENT_SUBSCRIBER_TAG = 'doctrine.event_subscriber';

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $loader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $containerBuilder->registerForAutoconfiguration(EventSubscriber::class)
            ->addTag(self::DOCTRINE_EVENT_SUBSCRIBER_TAG);
    }
}