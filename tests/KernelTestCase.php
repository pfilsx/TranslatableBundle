<?php


namespace Pfilsx\Translatable\Tests;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{

    /**
     * @var Application
     */
    protected $application;
    protected $em;

    protected function setUp(): void
    {

        $kernel = self::bootKernel();
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
        $this->application->run(new ArrayInput(array(
            'doctrine:schema:drop',
            '--force' => true
        )));
        $this->application->run(new ArrayInput(array(
            'doctrine:schema:create'
        )));
        $this->application->run(new ArrayInput(array(
            'doctrine:schema:update',
            '--force' => true
        )));
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function createEntityManager()
    {
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        return $this->em;
    }


    public function getEntityManager()
    {
        return $this->em ?? ($this->em = $this->createEntityManager());
    }
}
