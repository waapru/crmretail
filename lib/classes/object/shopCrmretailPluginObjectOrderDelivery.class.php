<?php

class shopCrmretailPluginObjectOrderDelivery implements ArrayAccess
{
    protected $data = array(
        'code' => '',       //	string		Код типа доставки
        'data' => [],       //	array		Данные для интеграционных типов доставки
        'service' => false,  //	object (SerializedDeliveryService)		Служба доставки
        'cost'  => 0.0,     //	double		Стоимость доставки
        'netCost' => 0.0,   //	double		Себестоимость доставки
        'date' => '',       //	DateTime		Дата доставки
        'time' => false,     //	object (DeliveryTime)		Информация о времени доставки
        'address' => false,  //	object (OrderDeliveryAddress)		Адрес доставки
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
                case 'service' :
                    $value = ( $value instanceof shopCrmretailPluginObjectDeliveryService ) ? (string)$value : null;
                    break;
                case 'time' :
                    $value = ( $value instanceof shopCrmretailPluginObjectDeliveryTime ) ? $value->get() : null;
                    break;
                case 'address' :
                    if ( $value instanceof shopCrmretailPluginObjectOrderDeliveryAddress )
                        $value = $value->get();
                    elseif ( is_string($value) )
                        $this->data['address']['text'] = $value;
                    return;
                case 'cost' :
                case 'netCost' :
                    $value = (float)$value;
                    break;
                case 'date' :
                    $value = ( $w = strtotime($value) ) ? date('Y-m-d H:i:s',$w) : '';
                    break;
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
        return json_encode($this->data);
    }


    public function toArray()
    {
        return array_filter($this->data);
    }
}

