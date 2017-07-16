<?php 
require 'config.php';

    $storeId = STORE_ID;
    $daysToExpire = EXPIRY_DATE;
    $orderId = ORDER_PREFIX;
	
    $live = LIVE;
	$easypayIndexPage = '';
	if ($live == 'no') {
		$easypayIndexPage = 'https://easypaystg.easypaisa.com.pk/easypay/Index.jsf';
	} else {
		$easypayIndexPage = 'https://easypay.easypaisa.com.pk/easypay/Index.jsf';
	}

    $merchantConfirmPage = HOST.'/easypay/confirmEasypay.php';

	$autoRedirect = AUTO_REDIRECT;
	//$autoRedirect = checked( isset( $options['autoRedirectCb'] ) );
   // $autoRedirect = isset( $options['autoRedirectCb']);	
	if($autoRedirect) {
		$autoRedirect = 1;
	} else {
		$autoRedirect = 0;
	}	
	
    $orderId .= $_GET['orderId'];
	if (strpos($_GET['amount'], '.') !== false) {
		$amount = $_GET['amount'];
	} else {
		$amount = sprintf("%0.1f",$_GET['amount']);
	}
	
	$custEmail = $_GET['custEmail'];
	$custCell = $_GET['custCell'];
	$hashKey = HASH_KEY;
	
    $currentDate = new DateTime();
    $currentDate->modify('+ 10 day');
    $expiryDate = $currentDate->format('Ymd His');
	
	$paymentMethods = PAYMENT_METHOD;
	$paymentMethodVal = $paymentMethods['methods'];
	
	$hashRequest = '';
	if(strlen($hashKey) > 0 && (strlen($hashKey) == 16 || strlen($hashKey) == 24 || strlen($hashKey) == 32 )) {
		// Create Parameter map
		$paramMap = array();
		$paramMap['amount']  = $amount ;
		$paramMap['autoRedirect']  = $autoRedirect ;
		if($custEmail != null && $custEmail != '') {
			$paramMap['emailAddr']  = $custEmail ;
		}
		if($expiryDate != null && $expiryDate != '') {
			$paramMap['expiryDate'] = $expiryDate;
		}
		if($custCell != null && $custCell != '') {
			$paramMap['mobileNum'] = $custCell;
		}
		$paramMap['orderRefNum']  = $orderId ;
		
		if($paymentMethodVal != null && $paymentMethodVal != '') {
			$paramMap['paymentMethod']  = $paymentMethodVal ;
		}		
		$paramMap['postBackURL'] = $merchantConfirmPage;
		$paramMap['storeId']  = $storeId ;
		
		//Creating string to be encoded
		$mapString = '';
		foreach ($paramMap as $key => $val) {
			$mapString .=  $key.'='.$val.'&';
		}
		$mapString  = substr($mapString , 0, -1);
		
		// Encrypting mapString
		function pkcs5_pad($text, $blocksize) {
			
			$pad = $blocksize - (strlen($text) % $blocksize);
			return $text . str_repeat(chr($pad), $pad);
			
		}

		$alg = MCRYPT_RIJNDAEL_128; // AES
		$mode = MCRYPT_MODE_ECB; // ECB

		$iv_size = mcrypt_get_iv_size($alg, $mode);
		$block_size = mcrypt_get_block_size($alg, $mode);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);	

		$mapString = pkcs5_pad($mapString, $block_size);
		$crypttext = mcrypt_encrypt($alg, $hashKey, $mapString, $mode, $iv);
		$hashRequest = base64_encode($crypttext);
	}
	
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if (!$con) {
            die('Could not connect: ' . mysqli_errno());    
    }     
    mysqli_select_db($con, DB_NAME);
	$table_name = 'easypay_order';
	
    // mysql inserting an order with pending status
    $query = "INSERT INTO ".$table_name."( easypay_order_id, easypay_order_info, easypay_order_status, ipn_attr ) VALUES ('$orderId' ,'null',  'pending',  'null')";
    try {
        mysqli_query($con, $query);
    } catch (Exception $ex) {            
        error_log($ex->getMessage());
    }

?>
<form name="easypayform" action="https://easypaystg.easypaisa.com.pk/easypay/Index.jsf" method="POST">
<! -- Store Id Provided by Easypay-->
<input name="storeId" value="3223" hidden = "true"/>
<! -- Amount of Transaction from merchant’s website -->
<input name="amount" value="1033" hidden = "true"/>
<! – Post back URL from merchant’s website -- >
<input name="postBackURL" value=" https://www.consuldents.com/easypay/confirmEasypay.php" hidden = "true"/>
<! – Order Reference Number from merchant’s website -- >
<input name="orderRefNum" value="1101" hidden = "true"/>
<! – Expiry Date from merchant’s website (Optional) -- >
<input type ="hidden" name="expiryDate" value="20170720 201521">
<! – Merchant Hash Value (Optional) -- >
<input type ="hidden" name="merchantHashedReq" value="askldjflaksdjflkasdf======asdfas dfkjaskdf">
<! – If Merchant wants to redirect to Merchant website after payment completion (Optional) -- >
<input type ="hidden" name="autoRedirect" value="0">
<! – If merchant wants to post specific Payment Method (Optional) -- >
<input type ="hidden" name="paymentMethod" value="">
<! – If merchant wants to post specific Payment Method (Optional) -- >
<input type ="hidden" name="emailAddr" value="shaan@uexel.com">
<! – If merchant wants to post specific Payment Method (Optionl) -- >
<input type ="hidden" name="mobileNum" value="03345385426">

<!-- <input type = "submit" value="Submit"> -->
</form>
<!-- <form name="easypayformx" method="post" action="<?php echo $easypayIndexPage ?>">
    <input name="storeId" value="<?php echo $storeId ?>" />
    <input name="amount" value="<?php echo $amount ?>" />
    <input name="postBackURL" value="<?php echo $merchantConfirmPage ?>" />
    <input name="orderRefNum" value="<?php echo $orderId ?>"/>
    <input name="expiryDate" value="<?php echo $expiryDate ?>" /> 
	<input name="autoRedirect" value="<?php echo $autoRedirect ?>" />
	<input name="emailAddr" value="<?php echo $custEmail ?>" />
	<input name="mobileNum" value="<?php echo $custCell ?>" />
	<input name="merchantHashedReq" value="<?php echo $hashRequest ?>" />
	<input name="paymentMethod" value="<?php echo $paymentMethodVal ?>" />
<input type = "submit" value="Submit">
</form> -->


<script data-cfasync="false" type="text/javascript">
    document.easypayform.submit();
</script>
