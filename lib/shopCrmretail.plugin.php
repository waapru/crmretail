<?php

class shopCrmretailPlugin extends shopPlugin
{
    public function orderActionCreate($params)
    {
        waLog::dump($params, 'shop/crmretail/order-actions/create.log');
    }
}
