<?php


namespace Pfilsx\FormLayer\Tests;


use Pfilsx\Translatable\TranslatableBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TranslatableBundleTest extends TestCase
{
    public function testBundle()
    {
        $bundle = new TranslatableBundle();
        $container = new ContainerBuilder();
        $bundle->build($container);
        $this->assertEquals('TranslatableBundle', $bundle->getName());
    }
}
