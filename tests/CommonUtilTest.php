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
          
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => '0'];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => '0'];

            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "ALERT" , 'sequenceId' => 1 ]), 
                json_encode($common->checkStatusFromDb(50000 ,  $dbValue , 1 ))
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
          
                
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1'];
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1' ];
                
                $this->assertJsonStringEqualsJsonString ( 
                    json_encode(['sensorStatus' => "OK" , "currentStatus" => "OK" , 'sequenceId' => '' ]), 
                    json_encode($common->checkStatusFromDb(1 ,  $dbValue , 2323))
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
          
            
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => '' ];
                $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => ''];

                $this->assertJsonStringEqualsJsonString ( 
                    json_encode(['sensorStatus' => "WARN" , "currentStatus" => "OK" , 'sequenceId' => '' ]), 
                    json_encode($common->checkStatusFromDb(123232 ,  $dbValue , 22 ))
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
            $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => '' ];
            $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => '' ];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => '' ];
            $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' , 'sequenceNumber' => ''];
            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "OK" , 'sequenceId' => '' ]), 
                json_encode($common->checkStatusFromDb(25000 ,  $dbValue , 2323 ))
            );
    }


    /**
     * Scenario 5 ::
     * Fresh Entry in
     * 
     * */

    public function testCanCheckScenario5StatusFromDb(): void 
    {
        $common = new Common();

        
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1'];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT', 'sequenceNumber' => '1' ];
            $dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1'];
            //$dbValue[] = ['sensorStatus' => 'OK' , 'sensorCurrentStatus' => 'OK' ];
            
            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "OK" , "currentStatus" => "ALERT" ,'sequenceId' => '1'  ]), 
                json_encode($common->checkStatusFromDb(1 ,  $dbValue , 12))
            );
        
   

    }

    /**
     * Scenario 6 ::
     * Fresh Entry for any sensor
     * */

    public function testCanCheckScenario6StatusFromDb(): void 
    {
        $common = new Common();

        
        
            $dbValue[] = [];
            
            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "WARN" , 'sequenceId' => '' ]), 
                json_encode($common->checkStatusFromDb(25000 ,  $dbValue , 1))
            );
        
   

    }


     /**
     * Scenario 7 ::
     * ALL IN ALERT ALREDY
     * 
     * */
    public function testCanCheckScenario7StatusFromDb(): void 
    {
            $common = new Common();
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1' ];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1' ];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1' ];
            $dbValue[] = ['sensorStatus' => 'WARN' , 'sensorCurrentStatus' => 'ALERT' , 'sequenceNumber' => '1'];
            $this->assertJsonStringEqualsJsonString ( 
                json_encode(['sensorStatus' => "WARN" , "currentStatus" => "ALERT" , 'sequenceId' => '1' ]), 
                json_encode($common->checkStatusFromDb(25000 ,  $dbValue , 2323 ))
            );
    }


    /**
     * Test format method
     * 
     * 
     * */
    public function testFormatOfAlertList(): void 
    {
            
       
            /*$array =  array(
                "startDate" => "2020-12-13 18:55:47",
                "endDate" => "2020-12-13 18:55:47",
                "co2value" => "20000,20000,20000,20000,20000,1,1,1"
            );*/

            $array[0] = array("startDate" => "2020-12-13 18:55:47", "endDate" => "2020-12-13 18:55:47" ,  "co2value" => "20000,20000,20000,20000,1,1,1");

            $common = new Common();
            $expectedOutput =  '[{
                "startDate": "2020-12-13 18:55:47",
                "endDate": "2020-12-13 18:55:47",
                "measurements": [
                    "20000",
                    "20000",
                    "20000",
                    "20000",
                    "1",
                    "1",
                    "1"
                ]
            }]';

            $this->assertJsonStringEqualsJsonString ( 
                $expectedOutput, 
                json_encode($common->formatAlertList($array))
            );
    }



}