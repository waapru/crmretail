<?php

class shopCrmretailPluginObjectOrderProduct implements ArrayAccess
{
    protected $data = array(
        'initialPrice' => 0.0,      //	double		Цена товара/SKU
        'discount' => 0.0,	        //  double		Скидка на одну единицу товара
        'discountPercent' => 0.0,   //	double		Процентная скидка на одну единицу товара
        'createdAt' => '',          //	DateTime	Дата создания товара в системе
        'quantity' => 0.0,       	//  float		Количество
        'comment' => '',	        //  string		Комментарий к товару в заказе
        'properties' => array(),         //  [массив]    Дополнительные свойства товара в заказе
        'purchasePrice' => 0.0,	    //  double		Закупочная цена
        'offer' => null,            //	object (SerializedOrderProductOffer)		Торговое предложение
        'productName' => '',        //	string		Название товара
        'status' => '',             //	string		Статус товара в заказе
        'priceType' => null,        //	object (PriceType)		Тип цены
    );

    // code     string	{not blank}{match: /^[a-zA-Z0-9_][a-zA-Z0-9_\-:]*$/D}}	Код свойства (не обязательное поле, код может передаваться в ключе свойства)
    // name     string	{not blank}	Имя свойства
    // value    string	{not blank}	Значение свойства
    public function setProperty($code,$name = null,$value = null)
    {
        if ( is_array($code) )
            extract($code);
        if ( empty($code) || empty($name) || empty($value) )
            return false;

        if ( preg_match('^[a-zA-Z0-9_][a-zA-Z0-9_\-:]*$',$code) )
            $this->data['properties'][] = compact($code,$name,$value);
        else
            return false;
        return true;
    }

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
                case 'properties' :
                    $value = $this->setProperty($value);
                    break;
                case 'offer' :
                    $value = ( $value instanceof shopCrmretailPluginOrderProductOffer ) ? $value->get() : null;
                    break;
            }
            $this->data[$offset] = $value;
        }
    }


    public function offsetUnset($offset)
    {
        $this->data[$offset] = '';
    }

    public function toArray()
    {
        return array_filter($this->data);
    }
}
