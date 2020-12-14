<?php
namespace App\Utils;

/**
 * Defining the entire business logic in this class
 * @author Prakhar Tiwari
 *  
 */

class Common
{
    
    /**
     * Defining MAX PPM Level of CO2 
     * (this can be increased in future if requirement changes)
     */
    private $maxPpmLevel = 2000;
    
    /**
     * Defining the Status String values (Keeping the names dynamic)
     */
    private $warningStatus = "WARN";
    private $okStatus = "OK";
    private $alertStatus = "ALERT";

    /**
     * Dynamic Variable for setting the LIMIT 
     * (this can be increased in future if requirement changes)
     * @method calcuateStatus
     * @param $co2Value int
     * */
    private $consecutiveLimit = 3;

    

    public function calcuateStatus($co2Value){
        
        if( $co2Value >  $this->maxPpmLevel  ){
            return $this->warningStatus;
        }else{
            return $this->okStatus;
        }
        
    }

    /**
     * Method is used to get status of the sensor while inserting the data
     * Main business logic is defined in this method 
     * @method checkStatusFromDb()
     * @param $co2Value int
     * @param $statusFromDB array 
     * 
     */


    public function checkStatusFromDb($co2Value, $statusFromDB){
    
        //Check there is any data in DB or not
        if(!empty($statusFromDB) && count($statusFromDB) > ($this->consecutiveLimit-2) ){

           $sensorStatusNow = $this->calcuateStatus($co2Value);
           $currentStatus = $this->okStatus;
           
           //if sensor is in ALERT state already        
            if ($statusFromDB[0]['sensorCurrentStatus'] == $this->alertStatus) {

                        //Check if we can set status back to OK 
                        if($sensorStatusNow == $this->okStatus){
                            $checkSingleInstance = "";
                            for($i=0; $i < $this->consecutiveLimit-1; $i++){
                                
                                if ( ($statusFromDB[$i]['sensorStatus'] == $this->okStatus) ){
                                    $currentStatus = $this->okStatus;
                                }else{
                                    $currentStatus = $this->alertStatus;
                                    break;
                                }
                               
                            }
                        }else{
                            $currentStatus = $this->alertStatus;
                        }
            }
            else{

                //Check if we can set to ALERT
                if($sensorStatusNow == $this->warningStatus){
                    for($i=0; $i < $this->consecutiveLimit-1; $i++){
                        if (  ($statusFromDB[$i]['sensorStatus'] == $this->warningStatus) ){
                            $currentStatus = $this->alertStatus;
                        }else{
                            $currentStatus = $this->okStatus;
                            break;
                        }
                        
                       
                    }
                    
                }
            }

        } 

        else{
            //if It's new entery in DB then we can't check previous status
            //Setting the calculated values in that case
            $sensorStatus = $this->calcuateStatus($co2Value);
            $currentStatus = ( isset($statusFromDB[0]['sensorStatus']) && $statusFromDB[0]['sensorStatus']!='')?  $statusFromDB[0]['sensorCurrentStatus'] : $sensorStatus;
        }
        return ['sensorStatus' => $this->calcuateStatus($co2Value) , 'currentStatus' => $currentStatus ];

    }
}