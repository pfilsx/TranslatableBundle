<?php

namespace Pfilsx\Translatable\Tests\app\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pfilsx\Translatable\Tests\app\Entity\Translation\NodeTranslation;

/**
* @method NodeTranslation|null find($id, $lockMode = null, $lockVersion = null)
* @method NodeTranslation|null findOneBy(array $criteria, array $orderBy = null)
* @method NodeTranslation[]    findAll()
* @method NodeTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class NodeTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeTranslation::class);
    }
}