<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/doc.json' => [[['_route' => 'app.swagger', '_controller' => 'nelmio_api_doc.controller.swagger'], null, ['GET' => 0], null, false, false, null]],
        '/api/doc' => [[['_route' => 'app.swagger_ui', '_controller' => 'nelmio_api_doc.controller.swagger_ui'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/v1/sensors/([^/]++)(?'
                    .'|(*:69)'
                    .'|/(?'
                        .'|me(?'
                            .'|trics(*:90)'
                            .'|surements(*:106)'
                        .')'
                        .'|alerts(*:121)'
                    .')'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        69 => [[['_route' => 'app_api_sensor_status', '_controller' => 'App\\Controller\\ApiController::sensor_status'], ['uuid'], ['GET' => 0], null, false, true, null]],
        90 => [[['_route' => 'app_api_sensor_metrics', '_controller' => 'App\\Controller\\ApiController::sensor_metrics'], ['uuid'], ['GET' => 0], null, false, false, null]],
        106 => [[['_route' => 'app_api_collect_sensor_mesurements', '_controller' => 'App\\Controller\\ApiController::collect_sensor_mesurements'], ['uuid'], ['POST' => 0], null, false, false, null]],
        121 => [
            [['_route' => 'app_api_listing_alerts', '_controller' => 'App\\Controller\\ApiController::listing_alerts'], ['uuid'], ['GET' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
