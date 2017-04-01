<?php

return array(
    // Системные настройки
    'status' => array(
        'title' => 'Включить плагин',
        'description' => '',
        'control_type' => waHtmlControl::CHECKBOX,
        'value' => 1,
        'subject' => 'system'
    ),
    'shopname' => array(
        'title' => 'Название магазина',
        'description' => '',
        'control_type' => waHtmlControl::INPUT,
        'value' => '',
        'subject' => 'system'
    ),
    'companyname' => array(
        'title' => 'Название компании',
        'description' => '',
        'control_type' => waHtmlControl::INPUT,
        'value' => '',
        'subject' => 'system'
    ),
    'siteurl' => array(
        'title' => 'Адрес сайта',
        'description' => '',
        'control_type' => waHtmlControl::INPUT,
        'value' => '',
        'subject' => 'system'
    ),

    // Настройки соединения
    'url' => array(
        'title' => 'Адрес RetailCRM',
        'description' => '',
        'control_type' => waHtmlControl::INPUT,
        'value' => '',
        'subject' => 'connection'
    ),
    'key' => array(
        'title' => 'Ключ авторизации',
        'description' => '',
        'control_type' => waHtmlControl::INPUT,
        'value' => '',
        'subject' => 'connection'
    ),
  
    
);