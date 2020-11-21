<?php

/* Template Name: Azul - Order Complete*/


get_template_part('header'); 


 if (isset($_GET['ResponseMessage'])) {
	$ResponseMessage = $_GET['ResponseMessage'];
	}

if (isset($_GET['OrderNumber'])) {
  $OrderNumber = $_GET['OrderNumber'];
  }

if (isset($_GET['AuthorizationCode'])) {
        $AuthorizationCode = $_GET['AuthorizationCode'];
        }
		
  $order = new WC_Order( $OrderNumber );
  
	// the email we want to send
	$email_class = 'WC_Email_Customer_Completed_Order';

	// load the WooCommerce Emails
	$wc_emails = new WC_Emails();
	$emails = $wc_emails->get_emails();

	// select the email we want & trigger it to send
	$new_email = $emails[$email_class];
	
	//if( $order->get_status() != "completed"){
		$body = $new_email->trigger($OrderNumber);
	//}
			
	echo '<div id="orderDetails" style="margin: 0 auto; width: 80%; margin-bottom: 50px; margin-top: 50px;">';
	
	echo '<div id="orderTitle"><b>Su orden fue completada, le enviaremos un correo electronico con los detalles del pedido. </b><br /><br /></div>';
	echo '<div id="orderNumber"><b>Numero de orden: </b>' . $OrderNumber. '</div>';
	echo '<div id="orderTotal"><b>Monto Total: </b>' . $order->get_total() . '</div>';
	echo '<div id="orderAuth"><b>Numero de Autorizacion: </b>' . $AuthorizationCode . '</div>';
	
	echo '</div>';
	
	get_template_part('footer'); 
?>

	




















