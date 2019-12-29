<?php


namespace Pfilsx\Translatable\Tests\app\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Pfilsx\Translatable\Contract\TranslationInterface;
use Pfilsx\Translatable\Entity\TranslationTrait;

/**
 * @ORM\Entity(repositoryClass="Pfilsx\Translatable\Tests\app\Repository\NodeTranslationRepository")
 */
class NodeTranslation implements TranslationInterface
{
    use TranslationTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $content;

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
}