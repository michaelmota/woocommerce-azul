<?php

/* Template Name: Azul - Order Process*/

if( isset($_GET['AzulUrl']) ){
	$AzulUrl = $_GET['AzulUrl'];
}

if( isset($_GET['MerchantId']) ){
	$MerchantId = $_GET['MerchantId'];
}

if( isset($_GET['MerchantName']) ){
	$MerchantName = $_GET['MerchantName'];
}

if( isset($_GET['MerchantType']) ){
	$MerchantType = $_GET['MerchantType'];
}

if( isset($_GET['CurrencyCode']) ){
	$CurrencyCode = $_GET['CurrencyCode'];
}

if( isset($_GET['itbis']) ){
	$itbis = $_GET['itbis'];
}

if( isset($_GET['OrderNumber']) ){
	$OrderNumber = $_GET['OrderNumber'];
}

if( isset($_GET['Amount']) ){
	$Amount = $_GET['Amount'];
}

if( isset($_GET['ApprovedUrl']) ){
	$ApprovedUrl = $_GET['ApprovedUrl'];
}

if( isset($_GET['DeclinedUrl']) ){
	$DeclinedUrl = $_GET['DeclinedUrl'];
}
if( isset($_GET['CancelUrl']) ){
	$CancelUrl = $_GET['CancelUrl'];
}
if( isset($_GET['ResponsePostUrl']) ){
	$ResponsePostUrl = $_GET['ResponsePostUrl'];
}
if( isset($_GET['UseCustomField1']) ){
	$UseCustomField1 = $_GET['UseCustomField1'];
}
if( isset($_GET['CustomField1Label']) ){
	$CustomField1Label = $_GET['CustomField1Label'];
}

if( isset($_GET['CustomField1Value']) ){
	$CustomField1Value = $_GET['CustomField1Value'];
}

if( isset($_GET['UseCustomField2']) ){
	$UseCustomField2 = $_GET['UseCustomField2'];
}

if( isset($_GET['AuthHash']) ){
	$AuthHash = $_GET['AuthHash'];
}
	$form = '
			<form id="orderForm" method="POST" action="'. $AzulUrl .'">
					<input type="hidden" name="MerchantId" value="'. $MerchantId .'">
					<input type="hidden" name="MerchantName" value="'. $MerchantName .'">
					<input type="hidden" name="MerchantType" value="'. $MerchantType .'">
					<input type="hidden" name="CurrencyCode" value="'. $CurrencyCode .'">
					<input type="hidden" name="itbis" value="'. $itbis .'">
					<input type="hidden" name="OrderNumber" value="'. $OrderNumber .'">
					<input type="hidden" name="Amount" value="'. $Amount .'">
					<input type="hidden" name="ApprovedUrl" value="'.  $ApprovedUrl .'">
					<input type="hidden" name="DeclinedUrl" value="'. $DeclinedUrl .'">
					<input type="hidden" name="CancelUrl" value="'.  $CancelUrl .'">
					<input type="hidden" name="ResponsePostUrl" value="'. $ResponsePostUrl .'">
					<input type="hidden" name="UseCustomField1" value="'. $UseCustomField1 .'">
					<input type="hidden" name="CustomField1Label" value="'. $CustomField1Label .'">
					<input type="hidden" name="CustomField1Value" value="'. $CustomField1Value .'">
					<input type="hidden" name="UseCustomField2" value="'. $UseCustomField2 .'">
					<input type="hidden" name="AuthHash" value="'. $AuthHash .'">
				</form>
			';

echo  $form;

	echo '<script>
		document.getElementById("orderForm").submit();
	</script>';


?>
