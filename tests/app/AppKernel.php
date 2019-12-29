<?php


namespace Pfilsx\Translatable\Tests\app;


use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Pfilsx\Translatable\TranslatableBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{

    /**
     * Returns an array of bundles to register.
     *
     * @return iterable|BundleInterface[] An iterable of bundle instances
     */
    public function registerBundles()
    {
        $bundles = array(
            new FrameworkBundle(),
            new DoctrineBundle(),
            new MakerBundle(),
            new TranslatableBundle(),
        );
        return $bundles;
    }


    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    public function getCacheDir()
    {
        return __DIR__ . '/cache/' . $this->environment;
    }
}
