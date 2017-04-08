<?php

class shopCrmretailPlugin extends shopPlugin
{
    public function orderActionCreate($params)
    {
        $m = new shopOrderModel();
        $order = $m->getOrder($params['id'],true,true);
        //waLog::dump($order, 'shop/crmretail/order-actions/create.log');

        $m = new waAppSettingsModel();
        $orderFormat = htmlspecialchars($m->get('shop', 'order_format', '#100{$order.id}'), ENT_QUOTES, 'utf-8');

        $orderObject = new shopCrmretailPluginObjectOrder;
        $orderObject['externalId'] = $order['id'];
        $orderObject['number'] = preg_replace("/(\{.*\})/", $order['id'], $orderFormat);

        $createdAt = date('Y-m-d H:i:s',strtotime('-10 day'));
        $orderObject['createdAt'] = $createdAt; //$order['create_datetime'];

        $orderObject['discount'] = $order['discount'] > 0 ? $order['discount'] : 0;

        $customer = new shopCustomer($order['contact_id']);

        if ( isset($customer['contragentType']) )
            $orderObject['orderType'] = $customer['contragentType'] == 'individual' ? 'eshop-individual' : 'eshop-legal';

        $orderObject['customerId'] = $order['contact_id'];
        $orderObject['lastName'] = $customer['lastname'];
        $orderObject['firstName'] = $customer['firstname'];
        $orderObject['patronymic'] = $customer['middlename'];

        if ( !empty($customer['phone']) ) {
            $orderObject['phone'] = $customer['phone'][0]['value'];
            if ( count($customer['phone']) > 1 )
                $orderObject['additionalPhone'] = $customer['phone'][1]['value'];
        }

        if ( !empty($customer['email']) )
            $orderObject['email'] = $customer['email'][0]['value'];

        $orderObject['customerComment'] = $order['comment'];

        foreach ( $order['items'] as $v )
        {
            $item = $v['item'];
            $itemObject = new shopCrmretailPluginObjectOrderProduct;
            $itemObject['initialPrice'] = $item['price'];
            $itemObject['purchasePrice'] = $item['purchase_price'];
            $itemObject['quantity'] = $item['quantity'];
            $itemObject['productName'] = $item['name'];
            $orderObject['items'][] = $itemObject->toArray();
        }



        $plugin_id = 'crmretail';
        $plugin = wa()->getPlugin($plugin_id);
        $s = $plugin->getSettings();

        $client = new shopCrmretailPluginApiClient($s['url'],$s['key']);
        $response = $client->ordersCreate($orderObject,$s['shopcode']);
    }
}
