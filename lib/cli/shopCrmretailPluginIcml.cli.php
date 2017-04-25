<?php

class shopCrmretailPluginIcmlCli extends waCliController
{
    public function execute()
    {
        // php cli.php shop crmretailPluginIcml
        waLog::log('Создание ICML', 'shop/crmretail/icml.log');
        $icml = new shopCrmretailPluginIcml;
        $icml->generate();
    }

}