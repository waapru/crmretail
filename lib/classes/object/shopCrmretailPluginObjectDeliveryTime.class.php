<?php

class shopCrmretailPluginObjectDeliveryTime implements ArrayAccess
{
    protected $data = array(
        'from' => '',   //	DateTime		Время доставки "с"
        'to' => '',     //	DateTime		Время доставки "до"
        'custom' => '', //	string		Время доставки в свободной форме
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
                case 'from' :
                case 'to' :
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


    public function get()
    {
        return $this->data;
    }
}

