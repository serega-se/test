<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Statistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statistic>
 *
 * @method Statistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistic[]    findAll()
 * @method Statistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    public function save(Statistic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Statistic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateCounts(array $answers)
    {
        $this->getEntityManager()->getConnection()
                ->executeStatement(
                    "INSERT INTO statistic as s (id, answer_id, count_answers)
                        SELECT NEXTVAL('statistic_id_seq'),answer_id, count(answer_id)
                        FROM user_statistic
                        GROUP BY answer_id
                        ON CONFLICT (answer_id) DO UPDATE
                            SET count_answers = excluded.count_answers"
                );

        $this->getEntityManager()->getConnection()
            ->executeStatement(
                "INSERT INTO statistic as s (id, answer_id, fraction)
                    SELECT
                        NEXTVAL('statistic_id_seq'),answer_id, round(100 * count_answers/d.cnt,1)
                    FROM statistic s
                        LEFT JOIN answer a on a.id = s.answer_id
                        LEFT JOIN question q on q.id = a.question_id
                        left JOIN LATERAL (
                        SELECT
                            count(us1.id) as cnt
                        from question AS q1
                                 LEFT JOIN answer a1 on q1.id = a1.question_id
                                 LEFT JOIN user_statistic us1 on a1.id = us1.answer_id
                        WHERE q1.id = q.id
                        GROUP BY q1.id
                        ) AS d ON true
                    ON CONFLICT (answer_id) DO UPDATE
                        SET fraction = excluded.fraction
                    RETURNING 1"
            );
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAnswersStatictics(?int $questionId): array
    {
        return $this->getEntityManager()->getConnection()
            ->fetchAllAssociative(
                "SELECT a.answer_text, 
                        s.count_answers, 
                        s.fraction
                        FROM answer as a
                LEFT JOIN statistic s on a.id = s.answer_id
                WHERE a.question_id = :questionId",
                ["questionId" => $questionId]
            );
    }

//    /**
//     * @return Statistic[] Returns an array of Statistic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Statistic
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
