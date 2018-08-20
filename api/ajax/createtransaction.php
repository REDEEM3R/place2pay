<?php

    require_once '../connect.php';

    $data = $_POST['data'];
    $data = json_decode($data);

    createPlace2Pay();

    $dateRequest = $_POST['date']/1000;
    date_default_timezone_set('America/Bogota');
    $transactionDate = date('c',$dateRequest);

    $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";

    $seed = date('c');
    $key = sha1($seed.'024h1IlD', false);

    $soap_options = array(
    'trace'       => 1,     // traces let us look at the actual SOAP messages later
    'exceptions'  => 1 
);

    $element = new stdClass;
    $element->auth = new stdClass;
    $element->auth->login = "6dd490faf9cb87a9862245da41170ff2";
    $element->auth->tranKey = $key;
    $element->auth->seed = $seed;
    $element->auth->additional = [];
    $element->transaction = new stdClass;
    $element->transaction->bankCode = $data->bank;
    $element->transaction->bankInterface = $data->interface;
    $element->transaction->returnURL = 'http://localhost/place2pay/src/html/success.html';
    $element->transaction->reference = hash('md2',$seed);
    $element->transaction->description = 'Prueba';
    $element->transaction->language = 'ES';
    $element->transaction->currency = 'COP';
    $element->transaction->totalAmount = 117000;
    $element->transaction->taxAmount = 19000;
    $element->transaction->devolutionBase = 2000;
    $element->transaction->tipAmount = 0;
    $element->transaction->payer = new stdClass;
    $element->transaction->payer->documentType = $data->documenttype;
    $element->transaction->payer->document = $data->documentno;
    $element->transaction->payer->firstName = $data->fname;
    $element->transaction->payer->lastName = $data->lname;
    $element->transaction->payer->company = '';
    $element->transaction->payer->emailAddress = $data->email;
    $element->transaction->payer->address = "";
    $element->transaction->payer->city = "";
    $element->transaction->payer->province = "";
    $element->transaction->payer->country = "";
    $element->transaction->payer->phone = $data->phone;
    $element->transaction->payer->mobile = "";
    $element->transaction->payer->postalCode = "";
    $element->transaction->buyer = new stdClass;
    $element->transaction->buyer = $element->transaction->payer;
    $element->transaction->shipping = new stdClass;
    $element->transaction->shipping = $element->transaction->payer;
    $element->transaction->ipAddress = $_SERVER['REMOTE_ADDR'];
    $element->transaction->userAgent = $_SERVER['HTTP_USER_AGENT'];
    $element->transaction->additionalData = [];

    $client = new SoapClient($wsdl, $soap_options);
    try {
        $result = $client->createTransaction($element);
        $result->createTransactionResult;
        if($result->createTransactionResult->returnCode == "SUCCESS"){
            $result->createTransactionResult->dateRequested = $transactionDate;
            $result->createTransactionResult->ipAddress = $_SERVER['REMOTE_ADDR'];
            $transactionID = insertId($result->createTransactionResult);
            if($transactionID){
                echo(json_encode($result->createTransactionResult));
            }
        }else{
            echo(json_encode([$result->createTransactionResult]));
        }
    }catch (SOAPFault $f) {
        // getBankList no retorna lo esperado
        // $res = ["banklist"=>"No se pudo obtener la lista de Entidades Financieras, por favor intente más tarde","msg"=>"ERROR"];
        print_r($f);
    }




?>