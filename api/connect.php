<?php

    date_default_timezone_set('America/Bogota');
    $dateRequest = $_POST['date']/1000;
    $transactionDate = date('c',$dateRequest);

    function createPlace2Pay(){
        $mysqli = new mysqli("localhost", "root", "root");    
        $query = "CREATE DATABASE IF NOT EXISTS place2pay-web";
        $create = $mysqli->query($query);
        mysqli_close($mysqli);
        $newmysql = new mysqli("localhost", "root", "root", "place2pay-web");
        $table = "CREATE TABLE IF NOT EXISTS `transactions` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `transactionID` int(64) DEFAULT NULL,
            `daterequested` int(11) DEFAULT NULL,
            `ipaddress` varchar(64) DEFAULT NULL,
            `status` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `transactionID` (`transactionID`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
        $table = $newmysql->query($table);
    }


    function insertId($data){
        $mysqli = new mysqli("localhost", "root", "root", "place2pay-web");
        $transactionID = $data->transactionID;
        $transactionDate = time();
        $transactionIP = $data->ipAddress;
        $transactionStatus = $data->responseCode;
        $inssql =   "INSERT INTO transactions (transactionid, daterequested, ipaddress, status) VALUES (".$transactionID.", ".$transactionDate.", '".$transactionIP."', '".$transactionStatus."')";
        $insert = $mysqli->query($inssql);
        if($insert==true){
            return 'OK';
        }
    }

    function getId(){
        $response = ["data"=> [], "msg"=>''];
        $currTime = time();
        $newmysql = new mysqli("localhost", "root", "root", "place2pay-web");
        $qry = "SELECT *, ($currTime - daterequested)/60  AS timeelapsed FROM transactions WHERE ipAddress = '".$_SERVER["REMOTE_ADDR"]."' order by id desc limit 1";
        // return $qry;
        $getQry = $newmysql->query($qry);
        if($getQry->num_rows > 0){
            while($row = $getQry->fetch_assoc()){
                array_push($response["data"],$row);
            }
            $response["msg"] .= "OK";
        }else{
            $response["msg"] .= "ERROR";
        }
        return $response;
    }

    function updateId($data){
        $newmysql = new mysqli("localhost", "root", "root", "place2pay-web");
        $qry = "UPDATE transactions set status = ".$data->responseCode." WHERE transactionID = ".$data->transactionID;
        $getQry = $newmysql->query($qry);
        if($getQry){
            return "OK";
        }
    }
    


?>