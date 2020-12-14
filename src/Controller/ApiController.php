<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use OpenApi\Annotations as OA;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Measurents;
use App\Utils\Common;
use DateTime;


/**
 * Controller to handle all request 
 * Defined 3 GET and 1 POST method
 * @author Prakhar Tiwari
 * 
 * 
 */


class ApiController extends AbstractController
{

    /**
     * Sensor status
     * 
     * Find sensor status by uuid
     * 
     * @Route("/api/v1/sensors/{uuid}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the all Possible status OK,WARN,ALERT",
     *    
     * )
     * @OA\Parameter(
     *     name="uuid",
     *     in="query",
     *     description="find status of sensor by uuid",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="GET")
     * 
     */

    public function sensor_status(int $uuid)
    {
        
        $lastSensorRecord = $this->getDoctrine()
                            ->getRepository(Measurents::class)
                            ->getSensorCurrentStatusByUuid($uuid);
        $status = null;
        if(!empty($lastSensorRecord)) $status = $lastSensorRecord[0]['sensorCurrentStatus'];
        return new JsonResponse(['status' => $status ]);

    }



    /**
     * Sensor Metrics
     * 
     * Providing the following metrics of a sensor
     * - Average CO2 level for the last 30 days
     * - Maximum CO2 Level in the last 30 days
     * @Route("/api/v1/sensors/{uuid}/metrics", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Average CO2 level of last 30 days and Maximum CO2 Level in the last 30 days",
   
     * )
     * @OA\Parameter(
     *     name="uuid",
     *     in="query",
     *     description="find matrics of sensor by uuid",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="GET")
     * 
    */


    public function sensor_metrics(int $uuid)
    {
        $lastSensorRecord = $this->getDoctrine()
                            ->getRepository(Measurents::class)
                            ->getSensorLast30DaysDataByUuid($uuid);
        if(!empty($lastSensorRecord)) {
                            
                return new JsonResponse(
                    [
                        'maxLast30Days' => $lastSensorRecord[0]['maxLast30Days'],
                        'avgLast30Days' => $lastSensorRecord[0]['avgLast30Days']
                
                    ]);
        }else{
            return new JsonResponse(
                [
                    'status' => false,
                    'msg' => 'No Data found'
            
                ]);

        }

    }


     /**
     * Listing alerts
     * 
     * @Route("/api/v1/sensors/{uuid}/alerts", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Average CO2 level of last 30 days and Maximum CO2 Level in the last 30 days",
 
     * )
     * @OA\Parameter(
     *     name="uuid",
     *     in="query",
     *     description="find list of last 3 alerts by uuid",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="GET")
     * 
    */

    public function listing_alerts(int $uuid){

        //Get all recent status from DB by uuid
        $dbData = $this->getDoctrine()
                ->getRepository(Measurents::class)
                ->getListOfAlerts($uuid);

         
                

        if(!empty($dbData)){
            $commonUtil = new Common();
            //formatData
            $measurents = $commonUtil->formatAlertList($dbData);
            return new JsonResponse($measurents);

        }else{
            return new JsonResponse(['status' => false , 'msg' => 'No Data Found']);
        }
        
    }

    /**
     * 
     * @Route("/api/v1/sensors/{uuid}/mesurements", methods={"POST"})
     * @OA\Tag(name="POST")

    * @OA\Post(
    *     summary="Take measurements of sensors by uuid",
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="co2",
    *                     type="int"
    *                 ),
    *                 @OA\Property(
    *                     property="time",
    *                     type="string"
    *                 ),
    *                 example={"co2": "1", "time": "2020-12-13T18:55:47+00:00"}
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK"
    *     )
    * )
    */

     public function collect_sensor_mesurements(int $uuid, Request $request){
       
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            if(empty($data)) die("Invalid Request");

            $commonUtil = new Common();

            //Validation
            //$commonUtil->validateData($data);
            
            
            //Get all recent status from DB by uuid
            $recentStatusDb = $this->getDoctrine()
                            ->getRepository(Measurents::class)
                            ->getSensorStatusBydescOrder($uuid);

            
            //Based on $recentStatus\'s data get current status for sensor
            $sensorStatus =  $commonUtil->checkStatusFromDb( $data['co2'] , $recentStatusDb , uniqid() );
            
            // Insert Data into databse
            $entityManager = $this->getDoctrine()->getManager();
            $measurents = new Measurents();
            //Setting the variables
            $measurents->setUuid($uuid);
            $measurents->setCo2value( $data['co2'] );          
            $dateTime = new DateTime( $data['time'] );
            $measurents->setDateTime( $dateTime   );
            $measurents->setSequenceNumber( $sensorStatus['sequenceId']   );
            $measurents->setSensorStatus( $sensorStatus['sensorStatus']  );
            $measurents->setSensorCurrentStatus( $sensorStatus['currentStatus']  );
            //$measurents->setAlertSequence( $sensorStatus['previousDataArr']  );
            $entityManager->persist($measurents);
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new JsonResponse(
                [ 
                    'status'  => true,
                    'lastInsertId' => $measurents->getId()
                
                ]
            
            );

        }

        return new JsonResponse(
            [ 
                'status'  => false,
                'msg'  => "Invalid Request, please Content-Type as application/json",
                'lastInsertId' => 0
            
            ]
        
        );


        

    }
}