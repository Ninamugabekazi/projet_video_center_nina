<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\SearchData;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

//    /**
//     * @return Video[] Returns an array of Video objects
//     */
public function paginationQuery($user)
{
    
        $qb = $this->createQueryBuilder('v')
        ->orderBy('v.id', 'ASC');
  
    if ($user && $user->isVerified()) {
        // L'utilisateur est connecté et vérifié, il peut voir toutes les vidéos premium et non premium
        $qb->andWhere('v.isPremiumVideo = :isPremiumVideo OR v.isPremiumVideo = :isNotPremiumVideo')
           ->setParameter('isPremiumVideo', true)
           ->setParameter('isNotPremiumVideo', false);
    } else {
        // L'utilisateur n'est pas connecté ou n'est pas vérifié, il peut voir uniquement les vidéos non premium
        $qb->andWhere('v.isPremiumVideo = :isPremiumVideo')
           ->setParameter('isPremiumVideo', false);
    }
  
    return $qb->getQuery();
      }

      public function findBySearch(SearchData $searchData)
      {
         $data = $this->createQueryBuilder('p')
         ->addOrderBy('p.createdAt','DESC');
         if(!empty($searchData->q)){
           $data=$data
           ->andWhere("p.title   LIKE :q OR p.description  LIKE :q")
           ->setParameter('q',"%{$searchData->q}%");
         }
         $data=$data
         ->getQuery();
       //   ->getResult();
   
       //   $voitures =$this-> $paginator->paginate($data,$searchData->page,5);
   
         return $data;
      }

//    public function findOneBySomeField($value): ?Video
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
