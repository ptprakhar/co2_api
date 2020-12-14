<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Measurents;

/**
 * @author Prakhar Tiwari
 * Test Class for Measurements Entity
 */

class MeasurentsEntityTest extends TestCase
{
    
    public function testCanGetTheUuid(): void
    {
        $measurents = new Measurents();
        $measurents->setUuid('100');
        $this->assertEquals ($measurents->getUuid() , '100' );
    }


    public function testCanGetTheCo2Value(): void
    {
        $measurents = new Measurents();
        $measurents->setCo2Value('25000');
        $this->assertEquals ($measurents->getCo2Value() , '25000' );
    }

    public function testCanGetSensorStatus(): void
    {
        $measurents = new Measurents();
        $measurents->setSensorStatus('WARN');
        $this->assertEquals ($measurents->getSensorStatus() , 'WARN' );
    }

    public function testCanGetSensorCurrentStatus(): void
    {
        $measurents = new Measurents();
        $measurents->setSensorCurrentStatus('WARN');
        $this->assertEquals ($measurents->getSensorCurrentStatus() , 'WARN' );
    }

    public function testCanDateTime(): void
    {
        $measurents = new Measurents();
        
        $dateTime = new DateTime( '2020-12-13T18:55:47+00:00' );
        $measurents->setDateTime( $dateTime);
        $this->assertEquals ($measurents->getDateTime() , $dateTime );
    }

    public function testCanGetId(): void
    {
        $measurents = new Measurents();
  
        $this->assertEquals ($measurents->getId() , null );
    }

    

}

