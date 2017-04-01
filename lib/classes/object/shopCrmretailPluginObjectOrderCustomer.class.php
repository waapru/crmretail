<?php

class shopCrmretailPluginObjectOrderCustomer implements ArrayAccess
{
    protected $data = array(
        'id' => 0,	        //  integer		Внутренний ID клиента
        'externalId' => 0,	//  integer		Внешний ID клиента
        'browserId' => '',	//  string		Идентификатор устройства в Collector
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
                case 'id' :
                case 'externalId' :
                    $value = (int)$value;
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
    
    public function get()
    {
        return $this->data;
    }
}
