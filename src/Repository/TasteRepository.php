<?php

namespace App\Repository;

use App\Entity\Taste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Taste>
 *
 * @method Taste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taste[]    findAll()
 * @method Taste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taste::class);
    }

    public function add(Taste $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Taste $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // TODO: make this work
//    public function findMatches($moviesCollect): array
//    {
//        return $this->createQueryBuilder('t')
//            ->select('count(t) nb, t.movie')
//            ->join('t.movie', 'm')
//            ->addSelect('m')
//            ->where('t.tasteStatus = true')
//            ->andWhere('m.id IN (:movies)')
//            ->setParameter('movies', $moviesCollect)
//            ->groupBy('m')
//            ->having('nb > 1')
//            ->orderBy('t.id', 'ASC')
//            ->getQuery()
//            ->getResult();
//    }

}
