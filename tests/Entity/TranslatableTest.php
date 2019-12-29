<?php


namespace Pfilsx\Translatable\Tests\Entity;


use Pfilsx\Translatable\Exception\UndefinedMethodException;
use Pfilsx\Translatable\Tests\app\Entity\Node;
use Pfilsx\Translatable\Tests\app\Entity\Translation\NodeTranslation;
use Pfilsx\Translatable\Tests\KernelTestCase;

class TranslatableTest extends KernelTestCase
{

    public function testGettersSetters()
    {
        $node = new Node();
        $node->setId(10)->setCreatedAt(new \DateTime())->setUser('TestUser');
        $node->setContent('en', 'Test Content');
        $node->setContent('ru', 'Тестовый контент');
        $this->assertCount(2, $node->getTranslations());
        $node->setContent('en', 'Test Content Edited');
        $this->assertCount(2, $node->getTranslations());
        $node->setDefaultLocale('en');
        $this->assertEquals('Test Content Edited', $node->getContent());
        $this->assertEquals('Test Content Edited', $node->getContent('en'));
        $this->assertEquals('Тестовый контент', $node->getContent('ru'));
        $this->assertEquals(null, $node->getContent('fr'));

        $this->expectException(UndefinedMethodException::class);
        $node->getCustomField();
    }

    public function testTranslations()
    {
        $node = new Node();
        $node->setId(10)->setCreatedAt(new \DateTime())->setUser('TestUser');
        $translation = new NodeTranslation();
        $translation->setContent('Test Content')->setLocale('en');
        $node->setTranslations([$translation]);
        $this->assertCount(1, $node->getTranslations());
        $this->assertSame($translation, $node->translate('en'));
        $this->assertSame($node, $translation->getTranslatable());
        $node->removeTranslation($translation);
        $this->assertEmpty($node->getTranslations());
    }
}