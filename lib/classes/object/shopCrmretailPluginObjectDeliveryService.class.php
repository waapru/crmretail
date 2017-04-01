<?php

class shopCrmretailPluginObjectDeliveryService implements ArrayAccess
{
    protected $data = array(
        'name' => '',           //	string		Название
        'code' => '',           //	string		Символьный код
        'active' => true,       //	boolean		Статус активности
        'deliveryType' => '',   //	string		Тип доставки
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
                case 'active' :
                    $value = !!$value;
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
}

