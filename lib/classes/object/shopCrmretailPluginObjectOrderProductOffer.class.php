<?php


class shopCrmretailPluginObjectOrderProductOffer implements ArrayAccess
{
    protected $data = array(
        'id' => 0,           //	integer		ID торгового предложения
        'externalId'=> '',   //	string		Внешний ID торгового предложения
        'xmlId' => '',       //	string		ID торгового предложения в складской системе
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

    public function get()
    {
        return $this->data;
    }
}