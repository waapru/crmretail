<?php

class shopCrmretailPluginObjectProductProperty implements ArrayAccess
{
    protected $data = array(
        'code' => '',   //	string	{not blank}{match: /^[a-zA-Z0-9_][a-zA-Z0-9_\-:]*$/D}}	Код свойства (не обязательное поле, код может передаваться в ключе свойства)
        'name' => '',	//  string	{not blank}	Имя свойства
        'value' => '',	//  string	{not blank}	Значение свойства
    );
    /*



order[items][][purchasePrice]	double		Закупочная цена
order[items][][offer]	object (SerializedOrderProductOffer)		Торговое предложение
order[items][][offer][id]	integer		ID торгового предложения
order[items][][offer][externalId]	string		Внешний ID торгового предложения
order[items][][offer][xmlId]	string		ID торгового предложения в складской системе
order[items][][productName]	string		Название товара
order[items][][status]	string		Статус товара в заказе
order[items][][priceType]	object (PriceType)		Тип цены
order[items][][priceType][code]	string		Код типа цены
     */

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
                case 'initialPrice' :
                case 'discount' :
                case 'quantity' :
                    $value = (float)$value;
                    break;
                case 'createdAt' :
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

    public function get()
    {
        return $this->data;
    }
}
