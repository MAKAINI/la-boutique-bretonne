<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
   
    /**
     * Undocumented function
     * @return Product[]
     */
    public function findWithSearch(Search $search){
        $squery = $this
                ->createQueryBuilder('p')
                ->select('c', 'p')
                ->join('p.category', 'c');
                if(!empty($search->categories)){
                    $squery = $squery
                             ->andWhere('c.id IN (:categories)')
                             ->setParameter('categorie', $search->categories);

                }
                if(!empty($search->categories)){
                    $squery = $squery
                             ->andWhere('p.name LIKE :string')
                             ->setParameter('string', "%{$search->string}%");
                }
        return $squery->getQuery()->getResult();

    }

//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
