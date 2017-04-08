<?php

class shopCrmretailIcmlCli extends waCliController
{
    public function execute()
    {
        waLog::log('Создание ICML', 'shop/crmretail/icml.log');
        $icml = new shopCrmretailPluginIcml;
        $icml->generate();
    }

}