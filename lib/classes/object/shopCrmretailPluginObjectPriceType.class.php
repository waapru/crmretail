<?php

class shopCrmretailPluginObjectPriceType implements ArrayAccess
{
    protected $data = array(
        'code' => '',   //	string		Код типа цены 
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
            $this->data[$offset] = $value;
        }
    }


    public function offsetUnset($offset)
    {
        $this->data[$offset] = '';
    }

    public function get()
    {
        return $this->data;
    }
}
