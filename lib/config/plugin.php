<?php

return array(
    'name'          => 'Retailcrm',
    'description'   => '',
    'vendor'        => '929600',
    'version'       => '1.0.0',
    'img'           => 'img/icon.png',
    'shop_settings' => true,
    'frontend'      => true,
    'handlers'      => array(
        'order_action.create' => 'orderActionCreate',
    ),
);