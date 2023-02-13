<?php

namespace App\Repository;

use App\Entity\UserStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserStatistic>
 *
 * @method UserStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStatistic[]    findAll()
 * @method UserStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStatistic::class);
    }

    public function save(UserStatistic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserStatistic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateStat(string $ipaddr, array $data):int
    {
        $effected = 0;
        foreach ($data as $index => $item) {
            $effected += $this->getEntityManager()->getConnection()
                ->executeStatement(
                    "INSERT INTO user_statistic as s (id, ipaddr, question_id, answer_id)
                         VALUES (NEXTVAL('user_statistic_id_seq'), :ipaddr, :questionId, :answerId)
                         ON CONFLICT (ipaddr, question_id) DO UPDATE
                         SET answer_id = excluded.answer_id
                         RETURNING 1",
                    [ 'ipaddr' => $ipaddr, 'questionId' => $index, 'answerId' => $item ]
                );
        }

        return $effected;
    }

//    /**
//     * @return UserStatistic[] Returns an array of UserStatistic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserStatistic
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
