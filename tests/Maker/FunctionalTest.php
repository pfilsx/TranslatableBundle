<?php


namespace Pfilsx\Translatable\Tests\Maker;


use Pfilsx\Translatable\Maker\MakeTranslatable;
use Pfilsx\Translatable\Tests\KernelTestCase;
use Symfony\Bundle\MakerBundle\Command\MakerCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class FunctionalTest extends KernelTestCase
{
    /**
     * @var string
     */
    private $app_path;

    protected function setUp(): void
    {
        $this->app_path = dirname(__DIR__) . '/app';
        parent::setUp();
    }

    public function testWiring()
    {
        $class = MakeTranslatable::class;
        $commandName = $class::getCommandName();
        $this->assertEquals('make:translatable', $commandName);
        $command = $this->application->find($commandName);
        $this->assertInstanceOf(MakerCommand::class, $command);
    }

    public function testMaker()
    {
        $name = 'TestNode';
        $input = new StringInput("make:translatable $name");
        $output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, true);
        $this->application->run($input, $output);
        $filePath = $this->app_path . "/Entity/$name.php";
        $translationFilePath = $this->app_path . "/Entity/Translation/{$name}Translation.php";
        $repositoryFilePath = $this->app_path . "/Repository/{$name}Repository.php";
        $translationRepositoryFilePath = $this->app_path . "/Repository/{$name}TranslationRepository.php";
        $this->assertTrue(is_file($filePath));
        $this->assertTrue(is_file($translationFilePath));
        $this->assertTrue(is_file($repositoryFilePath));
        $this->assertTrue(is_file($translationRepositoryFilePath));
        @unlink($filePath);
        @unlink($translationFilePath);
        @unlink($repositoryFilePath);
        @unlink($translationRepositoryFilePath);
    }
}
