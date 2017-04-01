<?php

class shopCrmretailPluginObjectSource implements ArrayAccess
{
    protected $data = array(
        'source' => '',     //	string		Источник
        'medium' => '',     //	string		Канал
        'campaign' => '',   //	string		Кампания
        'keyword' => '',    //	string		Ключевое слово
        'content' => '',    //	string		Содержание кампании
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


    public function __toString()
    {
        return json_encode($this->data);
    }
}

