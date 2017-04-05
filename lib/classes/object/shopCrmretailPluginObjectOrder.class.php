<?php

class shopCrmretailPluginObjectOrder implements ArrayAccess
{
    protected $data = array(
        'number' => '',         //	string		Номер заказа
        'externalId' => '',     //	string		Внешний ID заказа
        'countryIso' => '',     //	string		ISO код страны (ISO 3166-1 alpha-2)'
        'createdAt' => '',      //	DateTime	Дата оформления заказа
        'discount' => 0.0,	    //  double		Скидка в рублях
        'discountPercent' => 0, //	double		Скидка в %
        'mark' => 0,	        //  integer		Оценка заказа
        'markDatetime' => '',   //	DateTime	Дата и время получение оценки от покупателя
        'lastName' => '',	    //  string		Фамилия
        'firstName' => '',	    //  string		Имя
        'patronymic' => '',	    //  string		Отчество
        'phone' => '',	        //  string		Телефон
        'additionalPhone'=> '', //  string		Дополнительный телефон
        'email' => '',	        //  string		E-mail
        'call' => false,	    //  boolean		Требуется позвонить
        'expired' => false,	    //  boolean		Просрочен
        'customerComment' => '',//	string		Комментарий клиента
        'managerComment' => '',	//  string		Комментарий оператора
        'paymentDetail' => '',	//  string		Детали платежа
        'contragent' => null,   //  object (OrderContragent)		Реквизиты
        'statusComment' => '',	//  string		Комментарий к последнему изменению статуса
        'shipmentDate' => '',	//  DateTime	Дата отгрузки
        'shipped' => false,	    //  boolean		Заказ отгружен
        'customFields' => array(),	//  array		Ассоциативный массив пользовательских полей
        'orderType' => '',      //	string		Тип заказа
        'orderMethod' => '',	//  string		Способ оформления
        'customer' => null,	    //  object (SerializedOrderCustomer)		Клиент
        'managerId' => 0,	    //  integer		Менеджер, прикрепленный к заказу
        'paymentType' => '',	//  string		Тип оплаты
        'paymentStatus' => '',	//  string		Статус оплаты
        'status' => '',	        //  string		Статус заказа
        'items' => null,	    //  array of objects (SerializedOrderProduct)		Товар в заказе
        'delivery' => null,     //	object (SerializedOrderDelivery)		Данные о доставке
        'source' => null,       //	object (SerializedSource)		Источник заказа
        'shipmentStore' => '',  //	string		Склад отгрузки
    );
   

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }


    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }


    public function offsetSet($offset, $value)
    {
        if ( !is_null($offset) && isset($this->data[$offset]) )
        {
            switch ( $offset ) {
                case 'contragent' :
                    $value = ( $value instanceof shopRetailcrmPluginObjectOrderContragent ) ? $value->get() : null;
                    break;
                case 'customer' :
                    $value = ( $value instanceof shopCrmretailPluginObjectOrderCustomer ) ? (string)$value : null;
                    break;
                case 'source' :
                    $value = ( $value instanceof shopCrmretailPluginObjectSource ) ? (string)$value : null;
                    break;
                case 'customer' :
                    $value = ( $value instanceof shopCrmretailPluginObjectOrderCustomer ) ? $value->get() : null;
                    break;
                case 'markDatetime' :
                case 'shipmentDate' :
                case 'createdAt' :
                    $value = ( $w = strtotime($value) ) ? date('Y-m-d H:i:s',$w) : '';
                    break;
                case 'call' :
                case 'expired' :
                case 'shipped' :
                    $value = !!$value;
                    break;
                case 'discountPercent' :
                case 'mark' :
                case 'managerId' :
                    $value = (int)$value;
                    break;
                case 'discount' :
                    $value = (float)$value;
                    break;
                case 'items' :
                    if ( $value instanceof shopCrmretailPluginObjectOrderProduct )
                        $this->data['items'][] = $value;
                    return;
            }
            $this->data[$offset] = $value;
        }
    }


    public function offsetUnset($offset)
    {
        $this->data[$offset] = '';
    }


    public function __toString()
    {
        return json_encode(array_filter($this->data));
    }
}

