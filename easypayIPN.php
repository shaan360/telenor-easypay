<?php
require 'config.php';

    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if (!$con) {
        die('Could not connect: ' . mysqli_errno());    
    }     
    mysqli_select_db($con, DB_NAME);

    if (isset($_GET["url"])) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $_GET["url"]); 
        $output=curl_exec($curl);
		$table_name ='easypay_order';

        if($output != null) {

			$orderRefNumber = substr($_GET['url'], strrpos($_GET['url'], '/') + 1);
            $query = "UPDATE ".$table_name." SET ipn_attr='".$output."' WHERE easypay_order_id='".$orderRefNumber."'";
            
			try {
                mysqli_query($con, $query);
				echo "Response is saved ";
            } catch (Exception $ex) {            
                error_log($ex->getMessage());
            }		          
        }
        curl_close($curl);
    }
    else {
            echo "Welcome!! Enter url to get data :";
    }



