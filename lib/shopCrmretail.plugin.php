<?php

class shopCrmretailPlugin extends shopPlugin
{
    protected $d = array(
        10 => 'courier',
        11 => 'tk',
        12 => 'self-delivery',
        13 => 'edost',
        14 => 'ems',
    );

    protected $p = array(
        3 => 'bank-card',
        4 => 'cash',
        8 => 'shop-card',
        9 => 'bank-transfer',
    );

    public function orderActionCreate($params)
    {
        $m = new shopOrderModel();
        $order = $m->getOrder($params['id'],true,true);
        waLog::dump($order, 'shop/crmretail/order-actions/create.log');

        $m = new waAppSettingsModel();
        $orderFormat = htmlspecialchars($m->get('shop', 'order_format', '#100{$order.id}'), ENT_QUOTES, 'utf-8');

        $orderObject = new shopCrmretailPluginObjectOrder;
        $orderObject['externalId'] = $order['id'];
        $orderObject['number'] = preg_replace("/(\{.*\})/", $order['id'], $orderFormat);

        //$createdAt = date('Y-m-d H:i:s',strtotime('-10 day'));
        $orderObject['createdAt'] = $order['create_datetime']; //$createdAt;

        $orderObject['discount'] = $order['discount'] > 0 ? $order['discount'] : 0;

        $customer = new shopCustomer($order['contact_id']);

        if ( isset($customer['contragentType']) )
            $orderObject['orderType'] = 'eshop-individual';// $customer['contragentType'] == 'individual' ? 'eshop-individual' : 'eshop-legal';

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
            $orderObject['items'] = $itemObject;
        }

        if ( isset($order['params']['shipping_id']) && $order['params']['shipping_id'] )
        {
            $deliveryObject = new shopCrmretailPluginObjectOrderDelivery;
            $deliveryObject['cost'] = (float)$order['shipping'];
            $deliveryObject['code'] = $this->d[$order['params']['shipping_id']];
            $d = array();
            foreach ( array('country','city','street','km') as $v )
                if ( isset($order['params']['shipping_address.'.$v]) && !empty($order['params']['shipping_address.'.$v]) ){
                    $w = $order['params']['shipping_address.'.$v];
                    $d[] = ( $v == 'km' && intval($w) > 0 ) ? ($w.' км от МКАД') : $w;
                }

            if ( isset($order['params']['shipping_est_delivery']) )
            {
                $deliveryObject['date'] = $order['params']['shipping_est_delivery'];
                if ( empty($deliveryObject['date']) )
                    $d[] = $order['params']['shipping_est_delivery'];
            }

            $deliveryObject['address'] = implode(', ',$d);
            waLog::dump($deliveryObject, 'shop/crmretail/api_delivery.log');
            $orderObject['delivery'] = $deliveryObject;
        }

        if ( isset($order['params']['payment_id']) && $order['params']['payment_id'] )
            $orderObject['paymentType'] = $this->p[$order['params']['payment_id']];

        $s = $this->getSettings();
        $client = new shopCrmretailPluginApiClient($s['url'],$s['key']);
        waLog::dump($orderObject, 'shop/crmretail/api_order_create.log');
        $response = $client->ordersCreate($orderObject,$s['shopcode']);
        waLog::dump($response, 'shop/crmretail/api_response.log');
    }
}
