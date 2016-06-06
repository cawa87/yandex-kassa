<?php

namespace CawaKharkov\YandexKassa\Response;


class KassaXmlResponse extends Response
{


    public function __construct($code,$transactionId,$requestDatetime)
    {

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>');
        
        $xml->addChild('checkOrderResponse', [
            'performedDatetime' =>$requestDatetime,
            'code' =>$code,
            'invoiceId' =>$transactionId,
            'shopId' => config('yandex_kassa.shop.shopId'),
        ]);

        $header['Content-Type'] = 'application/xml';

        $this->make($xml->asXML(), 200, $header);
    }
    
    
    
}