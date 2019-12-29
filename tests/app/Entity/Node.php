<?php

namespace Pfilsx\Translatable\Tests\app\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pfilsx\Translatable\Contract\TranslatableInterface;
use Pfilsx\Translatable\Entity\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="Pfilsx\Translatable\Tests\app\Repository\NodeRepository")
 */
class Node implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $user;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
