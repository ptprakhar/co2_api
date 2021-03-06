<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format'], ['variable', '/', '\\d+', 'code'], ['text', '/_error']], [], []],
    'app_api_sensor_status' => [['uuid'], ['_controller' => 'App\\Controller\\ApiController::sensor_status'], [], [['variable', '/', '[^/]++', 'uuid'], ['text', '/api/v1/sensors']], [], []],
    'app_api_listing_alerts' => [['uuid'], ['_controller' => 'App\\Controller\\ApiController::listing_alerts'], [], [['text', '/metrics'], ['variable', '/', '[^/]++', 'uuid'], ['text', '/api/v1/sensors']], [], []],
    'app_api_collect_sensor_mesurements' => [['uuid'], ['_controller' => 'App\\Controller\\ApiController::collect_sensor_mesurements'], [], [['text', '/mesurements'], ['variable', '/', '[^/]++', 'uuid'], ['text', '/api/v1/sensors']], [], []],
    'app.swagger' => [[], ['_controller' => 'nelmio_api_doc.controller.swagger'], [], [['text', '/api/doc.json']], [], []],
    'app.swagger_ui' => [[], ['_controller' => 'nelmio_api_doc.controller.swagger_ui'], [], [['text', '/api/doc']], [], []],
];
