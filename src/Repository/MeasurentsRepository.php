<?php

namespace App\Repository;

use App\Entity\Measurents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @author Prakhar Tiwari
 * @method array[] getSensorStatusBydescOrder(int $uuid)
 * 
 */
class MeasurentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurents::class);
    }

    /**
     * Get all the values of sensors by uuid
     * @param uuid int
     * 
     */

    public function getSensorStatusBydescOrder(int $uuid): array
    {
        $entityManager = $this->getEntityManager();

         $qb = $this->createQueryBuilder('m')
                    ->select('m.id')
                    ->addSelect('m.uuid')
                    ->addSelect('m.co2value AS co2value')
                    ->addSelect('m.sensorStatus AS sensorStatus')
                    ->addSelect('m.sensorCurrentStatus AS sensorCurrentStatus')
                    ->addSelect('m.dateTime AS time')
                    ->where('m.uuid = :uuid')
                    ->setParameter('uuid', $uuid)
                    ->orderBy('m.id', 'DESC')
                    ->setMaxResults('4');
        $query = $qb->getQuery();
        return $query->getResult();

    }

    /**
     * Get sensor Current State(Status)by uuid
     * @param $uuid int
     */

    public function getSensorCurrentStatusByUuid(int $uuid): array
    {
        $entityManager = $this->getEntityManager();

         $qb = $this->createQueryBuilder('m')
                    ->select('m.id')
                    ->addSelect('m.uuid')
                    ->addSelect('m.co2value AS co2value')
                    ->addSelect('m.sensorStatus AS sensorStatus')
                    ->addSelect('m.sensorCurrentStatus AS sensorCurrentStatus')
                    ->where('m.uuid = :uuid')
                    ->setParameter('uuid', $uuid)
                    ->orderBy('m.id', 'DESC')
                    ->setMaxResults('1');
        $query = $qb->getQuery();
        return $query->getResult();

    }


    /**
     * Get last 30 days sensor data
     * @param $uuid int
     */

    public function getSensorLast30DaysDataByUuid(int $uuid): array
    {
        $entityManager = $this->getEntityManager();
        $rsm = new ResultSetMapping();
        $conn = $entityManager->getConnection();
        $sql = '
                SELECT AVG(co2value) as avgLast30Days, MAX(co2value) as maxLast30Days FROM measurents 
                WHERE 
                uuid = :uuid 
                and  
                date_time > now() - INTERVAL 30 day
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('uuid' => $uuid ));
        return $stmt->fetchAll();

    }


   
}
