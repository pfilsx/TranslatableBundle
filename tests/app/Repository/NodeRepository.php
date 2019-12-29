<?php

namespace Pfilsx\Translatable\Tests\app\Repository;

use Pfilsx\Translatable\Tests\app\Entity\Node;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
* @method Node|null find($id, $lockMode = null, $lockVersion = null)
* @method Node|null findOneBy(array $criteria, array $orderBy = null)
* @method Node[]    findAll()
* @method Node[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class NodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Node::class);
    }
}