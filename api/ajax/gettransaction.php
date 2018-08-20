<?php

    $response = array();

    require_once '../connect.php';

    createPlace2Pay();

    $transactionID = $_POST['transactionID'];

    $ip = $_SERVER["REMOTE_ADDR"];
    date_default_timezone_set('America/Bogota');


    if($transactionID != ''){
            $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";
            $seed = date('c');
            $key = sha1($seed.'024h1IlD', false);
            $soap_options = array(
            'trace'       => 1,     // traces let us look at the actual SOAP messages later
            'exceptions'  => 1 );
            
            $element = new stdClass;
            $element->auth = new stdClass;
            $element->auth->login = "6dd490faf9cb87a9862245da41170ff2";
            $element->auth->tranKey = $key;
            $element->auth->seed = $seed;
            $element->auth->additional = [];
            $element->transactionID = $transactionID;

            $client = new SoapClient($wsdl, $soap_options);
            
            $data = getId();
            $msg = $data["msg"];
            $data = $data["data"];
            if($msg == "OK"){
                if($data[0]["timeelapsed"] > 7){
                    if( $data[0]["ipaddress"] == $ip){
                        if( $data[0]["status"] == 3 ){
                            $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";
                            $seed = date('c');
                            $key = sha1($seed.'024h1IlD', false);
                            $soap_options = array(
                            'trace'       => 1,     // traces let us look at the actual SOAP messages later
                            'exceptions'  => 1 );
                            
                            $element = new stdClass;
                            $element->auth = new stdClass;
                            $element->auth->login = "6dd490faf9cb87a9862245da41170ff2";
                            $element->auth->tranKey = $key;
                            $element->auth->seed = $seed;
                            $element->auth->additional = [];
                            $element->transactionID = $data[0]["transactionID"];
                
                            $client = new SoapClient($wsdl, $soap_options);
                
                            try {
                                $result = $client->getTransactionInformation($element);
                                $result->getTransactionInformationResult;
                                $code = $result->getTransactionInformationResult->responseCode;
                                if($result->getTransactionInformationResult->returnCode == "SUCCESS"){
                                    if($result->getTransactionInformationResult->returnCode == "SUCCESS"){
                                        if($data[0]["status"] != $code){
                                            $upd = updateId($result->getTransactionInformationResult);
                                            if($code == 0){
                                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                            }else if($code == 1){
                                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                            }else if($code == 2){
                                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                            }
                                        }else{
                                            $response = ["data"=>$data, "msg"=>"WAITING"];
                                        }
                                    }
                                }
                            }catch (SOAPFault $f) {
                                print_r($f);
                            }
                        }else{
                            switch ($data[0]["status"]) {
                                case 0:
                                    $response = ["data"=>$data[0], "msg"=>"FAILED"];
                                    break;
                                case 1:
                                    $response = ["data"=>$data[0], "msg"=>"OK"];
                                    break;
                                case 2:
                                    $response = ["data"=>$data[0], "msg"=>"NOT_AUTHORIZED"];
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }else if( $data[0]["timeelapsed"] < 7) {
                    try {
                        $result = $client->getTransactionInformation($element);
                        $result->getTransactionInformationResult;
                        $code = $result->getTransactionInformationResult->responseCode;
                        if($result->getTransactionInformationResult->returnCode == "SUCCESS"){
                            if($data[0]["status"] != $code){
                                $upd = updateId($result->getTransactionInformationResult);
                                if($code == 0){
                                    $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                }else if($code == 1){
                                    $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                }else if($code == 2){
                                    $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                }
                            }else{
                                if($code == 0){
                                    $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                }else if($code == 1){
                                    $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                }else if($code == 2){
                                    $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                }else{
                                    $response = ["data"=>$data, "msg"=>"WAITING"];
                                }
                            }
                        }
                    }catch (SOAPFault $f) {
                        print_r($f);
                    }
                }else{
                    $response = ["data"=>$data, "msg"=>"WAITING"];
                }
            }else{
                $response["data"] = $data;
                $response["msg"] = "NOENTRY";
            }
    }else{


        $data = getId();
        $msg = $data["msg"];
        $data = $data["data"];


        if($msg == "OK"){
            $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";
            $seed = date('c');
            $key = sha1($seed.'024h1IlD', false);
            $soap_options = array(
            'trace'       => 1,     // traces let us look at the actual SOAP messages later
            'exceptions'  => 1 );
            
            $element = new stdClass;
            $element->auth = new stdClass;
            $element->auth->login = "6dd490faf9cb87a9862245da41170ff2";
            $element->auth->tranKey = $key;
            $element->auth->seed = $seed;
            $element->auth->additional = [];
            $element->transactionID = $data[0]["transactionID"];

            $client = new SoapClient($wsdl, $soap_options);
            
            if($data[0]["timeelapsed"] > 7){
                if( $data[0]["ipaddress"] == $ip){
                    if( $data[0]["status"] == 3 ){
                        $wsdl = "https://test.placetopay.com/soap/pse/?wsdl";
                        $seed = date('c');
                        $key = sha1($seed.'024h1IlD', false);
                        $soap_options = array(
                        'trace'       => 1,     // traces let us look at the actual SOAP messages later
                        'exceptions'  => 1 );
                        
                        $element = new stdClass;
                        $element->auth = new stdClass;
                        $element->auth->login = "6dd490faf9cb87a9862245da41170ff2";
                        $element->auth->tranKey = $key;
                        $element->auth->seed = $seed;
                        $element->auth->additional = [];
                        $element->transactionID = $data[0]["transactionID"];
            
                        $client = new SoapClient($wsdl, $soap_options);
            
                        try {
                            $result = $client->getTransactionInformation($element);
                            $result->getTransactionInformationResult;
                            $code = $result->getTransactionInformationResult->responseCode;
                            if($result->getTransactionInformationResult->returnCode == "SUCCESS"){
                                if($result->getTransactionInformationResult->returnCode == "SUCCESS"){
                                    if($data[0]["status"] != $code){
                                        $upd = updateId($result->getTransactionInformationResult);
                                        if($code == 0){
                                            $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                        }else if($code == 1){
                                            $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                        }else if($code == 2){
                                            $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                                        }
                                    }else{
                                        $response = ["data"=>$data, "msg"=>"WAITING"];
                                    }
                                }
                            }
                        }catch (SOAPFault $f) {
                            print_r($f);
                        }
                    }else{
                        switch ($data[0]["status"]) {
                            case 0:
                                $response = ["data"=>$data[0], "msg"=>"FAILED"];
                                break;
                            case 1:
                                $response = ["data"=>$data[0], "msg"=>"OK"];
                                break;
                            case 2:
                                $response = ["data"=>$data[0], "msg"=>"NOT_AUTHORIZED"];
                                break;
                            default:
                                break;
                        }
                    }
                }
            }else if( $data[0]["timeelapsed"] < 7) {
                try {
                    $result = $client->getTransactionInformation($element);
                    $result->getTransactionInformationResult;
                    $code = $result->getTransactionInformationResult->responseCode;
                    if($result->getTransactionInformationResult->returnCode == "SUCCESS"){
                        if($data[0]["status"] != $code){
                            $upd = updateId($result->getTransactionInformationResult);
                            if($code == 0){
                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                            }else if($code == 1){
                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                            }else if($code == 2){
                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                            }
                        }else{
                            if($code == 0){
                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                            }else if($code == 1){
                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                            }else if($code == 2){
                                $response = ["data"=>$result->getTransactionInformationResult,"msg"=>$result->getTransactionInformationResult->transactionState];
                            }else{
                                $response = ["data"=>$data, "msg"=>"WAITING"];
                            }
                        }
                    }
                }catch (SOAPFault $f) {
                    print_r($f);
                }
            }else{
                $response = ["data"=>$data, "msg"=>"WAITING"];
            }
        }else{
            $response = ["data"=>$data, "msg"=>"NOENTRY"];
        }
        
    }
  

    echo(json_encode($response));
    



?>