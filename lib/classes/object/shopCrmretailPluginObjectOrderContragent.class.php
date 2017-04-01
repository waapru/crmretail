<?php

class shopCrmretailPluginObjectOrderContragent implements ArrayAccess
{
    const INDIVIDUAL = 'individual';        // физическое лицо,
    const LEGAL_ENTITY = 'legal-entity';    // юридическое лицо,
    const ENTERPRENEUR = 'enterpreneur';    // индивидуальный предприниматель.

    protected $data = array(
        'contragentType' => self::ENTERPRENEUR, 	    //  string		Тип контрагента
        'legalName' => '',                  	        //  string		Полное наименование
        'legalAddress' => '',	                        //  string		Адрес регистрации
        'INN' => '',                                	//  string		ИНН
        'OKPO' => '',                       	        //  string		ОКПО
        'KPP' => '',	                                //  string		КПП
        'OGRN' => '',	                                //  string		ОГРН
        'OGRNIP' => '',	                                //  string		ОГРНИП
        'certificateNumber' => '',	                    //  string		Номер свидетельства
        'certificateDate' => '',	                    //  DateTime	Дата свидетельства
        'BIK' => '',	                                //  string		БИК
        'bank' => '',	                                //  string		Банк
        'bankAddress' => '',	                        //  string		Адрес банка
        'corrAccount' => '',	                        //  string		Корр. счёт
        'bankAccount' => '',	                        //  string		Расчётный счёт
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
                case 'contragentType' :
                    $value = in_array($value,array(self::INDIVIDUAL,self::LEGAL_ENTITY,self::ENTERPRENEUR)) ? $value : self::INDIVIDUAL;
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
