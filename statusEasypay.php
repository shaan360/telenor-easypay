<?php 
require 'config.php';

    $status = $_GET['status'];
    $orderRefNumber = $_GET ['orderRefNumber'];

    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if (!$con) {
        die('Could not connect: ' . mysqli_errno());    
    }     
    mysqli_select_db($con, DB_NAME);
	
	$table_name = 'easypay_order';
	
    if ($status == '0000') {       
        $query = "UPDATE ".$table_name." SET easypay_order_status='success' WHERE easypay_order_id='".$orderRefNumber."'";
    } else {
        $query = "UPDATE ".$table_name." SET easypay_order_status='failed' WHERE easypay_order_id='".$orderRefNumber."'";
    }

    try {
        mysqli_query($con, $query);
        header("Location: ".HOST);
die();
    } catch (Exception $ex) {            
        error_log($ex->getMessage());
    }  
