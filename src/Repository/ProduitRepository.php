<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Filter\ProduitFilter;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Produit $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findFiltre(ProduitFilter $filter)
    {
        $query = $this->createQueryBuilder("p");
        if($filter->recherche)
        {
            $query = $query
            ->andWhere("p.titre LIKE :recherche")
            ->orWhere("p.description LIKE :recherche")
            ->orWhere("p.prix LIKE :recherche")
            ->setParameter("recherche", "%$filter->recherche%");
        }

        if($filter->min)
        {
            $query = $query
            ->andWhere("p.prix >= :min")
            ->setParameter("min", $filter->min);

        }

        if($filter->max)
        {
            $query = $query
            ->andWhere("p.prix <= :max")
            ->setParameter("max", $filter->max);

        }

        if($filter->order)
        {
            switch($filter->order)
            {
                case 1:
                    $query = $query
                    ->orderBy("p.prix", "ASC")
                ;
                break;

                case 2:
                    $query = $query
                    ->orderBy("p.prix", "DESC")
                ;
                break;

                case 3:
                    $query = $query
                    ->orderBy("p.titre", "ASC")
                ;
                break;

                case 4:
                    $query = $query
                    ->orderBy("p.titre", "DESC")
                ;
                break;
            }

        }


        return $query->getQuery()->getResult();
    }
}

