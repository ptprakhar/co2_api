<?php

namespace App\Repository;

use App\Entity\Measurents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * Connecting to DB for fetching the data
 * @author Prakhar Tiwari
 *
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
     * @method getSensorStatusBydescOrde
     * @param $uuid int
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
                    ->addSelect('m.sequenceNumber AS sequenceNumber')
                    ->where('m.uuid = :uuid')
                    ->setParameter('uuid', $uuid)
                    ->orderBy('m.id', 'DESC')
                    ->setMaxResults('4');
        $query = $qb->getQuery();
        return $query->getResult();

    }

    /**
     * Get sensor Current State(Status)by uuid
     * @method getSensorCurrentStatusByUuid
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
     * @method getSensorLast30DaysDataByUuid()
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
