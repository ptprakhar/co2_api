<?php

use PHPUnit\Framework\TestCase;
use App\Utils\Common;

/**
 * @author Prakhar Tiwari
 * Test Class for CommonUtil 
 * Testing all the business logic
 */

class CommonUtilTest extends TestCase
{ 

    public function testCanCalcuateStatus(): void
    {
        $common = new Common();
        $this->assertEquals ('WARN' , $common->calcuateStatus(2500) );
    }

    public function testCanCalcuateOkStatus(): void
    {
        $common = new Common();
        $this->assertEquals ('OK' , $common->calcuateStatus(1) );
    }

        //sensorCurrentStatus = the stored value which DB holds as per the business logic
        //sensorStatus = the value which getting passed by the API (live value) and all previous live values (it will never have ALERT)
        //Set this value to test different scenarios

    

    /**
     * Scenario 1 ::
     * Passing 3 Warning together
     * and old status in DB is OK
     * Expected that sensor state (currentStatus) will move to "ALERT"
    * */

    public function testCanCheckScenario1StatusFromDb(): void 
    {
        $common = new Common();
          
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'OK' ];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'OK' ];

            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "ALERT" ]), 
                json_encode($common->checkStatusFromDb(50000 ,  $dbValue ))
            );


    }


    /**
     * Scenario 2 ::
     * Passing 3 OK together
     * and old status in DB is ALERT
     * Expected that sensor state (currentStatus) will move to "OK"
     * */

    public function testCanCheckScenario2StatusFromDb(): void 
    {
        $common = new Common();
          
                
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'ALERT' ];
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'ALERT' ];
                
                $this->assertJsonStringEqualsJsonString ( 
                    json_encode(['sensorStatus' => "OK" , "currentStatus" => "OK" ]), 
                    json_encode($common->checkStatusFromDb(1 ,  $dbValue ))
                );


    }


    /**
     * Scenario 3 ::
     * Passing 1 WARNING 2 OK are alredy there
     * and old status in DB is "OK"
     * Expected that sensor state (currentStatus) will rain in OK
    * */
    public function testCanCheckScenario3StatusFromDb(): void 
    {
                $common = new Common();
          
            
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' ];
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' ];

                $this->assertJsonStringEqualsJsonString ( 
                    json_encode(['sensorStatus' => "WARN" , "currentStatus" => "OK" ]), 
                    json_encode($common->checkStatusFromDb(123232 ,  $dbValue ))
                );


    }


    /**
     * Scenario 4 ::
     * Passing 1 WARNING 1 OK  and 1 WARNING 
     * and old status in DB is "OK"
     * Expected that sensor state (currentStatus) will ramain in OK
     * */
    public function testCanCheckScenario4StatusFromDb(): void 
    {
        $common = new Common();

        
        
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'OK' ];
            $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' ];

            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "OK" ]), 
                json_encode($common->checkStatusFromDb(25000 ,  $dbValue ))
            );
        
   

    }


    /**
     * Scenario 5 ::
     * Fresh Entry in
     * */

    public function testCanCheckScenario5StatusFromDb(): void 
    {
        $common = new Common();

        
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' ];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' ];
            $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'ALERT' ];
            //$dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' ];
            
            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "OK" , "currentStatus" => "ALERT" ]), 
                json_encode($common->checkStatusFromDb(1 ,  $dbValue ))
            );
        
   

    }

    /**
     * Scenario 6 ::
     * Fresh Entry in
     * */

    public function testCanCheckScenario6StatusFromDb(): void 
    {
        $common = new Common();

        
        
            $dbValue[] = [];
            
            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "WARN" ]), 
                json_encode($common->checkStatusFromDb(25000 ,  $dbValue ))
            );
        
   

    }


}