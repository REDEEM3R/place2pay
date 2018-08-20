<?php

    $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";

    $seed = date('c');
    $key = sha1($seed.'024h1IlD', false);

    $soap_options = array(
        'trace'       => 1,     
        'exceptions'  => 1 );

    $element = new stdClass;
    $element->auth = new stdClass;
    $element->auth->login = "6dd490faf9cb87a9862245da41170ff2";
    $element->auth->tranKey = $key;
    $element->auth->seed = $seed;
    $element->auth->additional = [];


    $client = new SoapClient($wsdl, $soap_options);
    
    if( $client){
        try {
            $result = $client->getBankList($element);
            $banks = array();
            foreach($result->getBankListResult->item as $bank){
                array_push($banks, array("bankdesc" => $bank->bankName, "bankcode" => $bank->bankCode));
            };

            $res = ["banklist"=>$banks,"msg"=>"OK"];
    
        } catch (SOAPFault $f) {
            $res = ["banklist"=>"No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde","msg"=>"ERROR"];
        }
    }
    echo(json_encode($res));



