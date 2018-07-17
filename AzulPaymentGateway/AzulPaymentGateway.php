<?php
/**
 * Plugin Name: WooCommerce Azul Payment Gateway
 * Plugin URI: https://alternadr.com/
 * Description: Plugin para aceptar pagos mediante Azul.
 * Version: 1.0.0
 */

 add_action( 'plugins_loaded', 'azul_payment_gateway' );

 function azul_payment_gateway() {

 	class AzulPaymentGateway extends WC_Payment_Gateway {

    public function __construct() {
		    	$this->id                 	= 'woo_azul';
		    	$this->method_title       	= __( 'Azul Payments Gateway', 'woodev_payment' );
		    	$this->method_description 	= __( 'Pasarela de pago con Azul del Banco Popular.', 'woo_azul' );
		    	$this->title              	= __( 'Pagos Azul', 'woo_azul' );
			    $this->has_fields = false;
    			$this->supports = array(
    				'products'
    			);


		   	// Load the settings.
        	$this->init_form_fields();
        	$this->init_settings();
		    	$this->enabled 		= $this->get_option('enabled');

          $this->title          = $this->get_option( 'title' );
          $this->description    = $this->get_option( 'description' );
          $this->testmode       = 'yes' === $this->get_option( 'testmode', 'no' );
          $this->debug          = 'yes' === $this->get_option( 'debug', 'no' );
          $this->authKey        = $this->get_option( 'authKey' );
          $this->merchantId     = $this->get_option( 'merchantId' );
          $this->merchantName   = $this->get_option( 'merchantName' );
          $this->merchantType   = $this->get_option( 'merchantType' );
          $this->processUrl   = $this->get_option( 'processUrl' );

		    	add_action( 'check_wooazul', array( $this, 'check_response') );
		    	// Save settings
  			if ( is_admin() ) {
  				// Versions over 2.0
  				// Save our administration options. Since we are not going to be doing anything special
  				// we have not defined 'process_admin_options' in this class so the method in the parent
  				// class will be used instead
  				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
  			}
		}

    public function get_return_url( $order ) {
      if ( $this->testmode ) {
        $this->azulUrl = 'https://pruebas.azul.com.do/PaymentPage/default.aspx';
      } else {
        $this->azulUrl = 'https://pagos.azul.com.do/PaymentPage/default.aspx';
      }
      return parent::get_return_url( $order );
    }

    /**
     * Initialize Gateway Settings for the Form Fields in WP Admin
     */
    public function init_form_fields() {

      $this->form_fields = array(
        'enabled' => array(
          'title'   => __( 'Habiliado/Deshabilitado', 'woo_azul' ),
          'type'    => 'checkbox',
          'label'   => __( 'Habilitar Azul', 'woo_azul' ),
          'default' => 'yes'
        ),
        'title' => array(
          'title'       => __( 'Titulo', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'El titulo visible por el usuario en la pagina de checkout.', 'woo_azul' ),
          'default'     => __( 'Azul', 'woo_azul' ),
          'desc_tip'    => true,
        ),
        'description' => array(
          'title'       => __( 'Descripción', 'woo_azul' ),
          'type'        => 'text',
          'desc_tip'    => true,
          'description' => __( 'Descripcion visible por el usuario en la pagina de checkout.', 'woo_azul' ),
          'default'     => __( 'Pago via Azul; puedes pagar usando tu tarjeta de credito o debito.', 'woo_azul' )
        ),
        'testmode' => array(
          'title'       => __( 'Sandbox', 'woo_azul' ),
          'type'        => 'checkbox',
          'label'       => __( 'Habilitar el modo de prueba.', 'woo_azul' ),
          'default'     => 'no',
          'description' => __('Habilita el sandbox de azul para realizar pruebas.', 'woo_azul' )
        ),
        'debug' => array(
          'title'       => __( 'Debug', 'woo_azul' ),
          'type'        => 'checkbox',
          'label'       => __( 'Habilitar logging', 'woo_azul' ),
          'default'     => 'no',
          'description' =>  __( 'Depura eventos y respuestas de Azul', 'woo_azul' )
        ),
        'authKey' => array(
          'title'       => __( 'AUTH_KEY', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Introducir el código de autorización que le entregó Azul.', 'woo_azul' ),
          'default'     => "",
          'desc_tip'    => true,
          'placeholder' => 'OgYw6jAQaegyGYPUAdAHy...'
        ),
        'merchantId' => array(
          'title'       => __( 'ID del Comerciante', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Inserte el ID del comerciante que le proporcionó Azul' , 'woo_azul' ),
          'default'     => '',
          'desc_tip'    => true,
          'placeholder' => '0123456789'
        ),
        'merchantName' => array(
          'title'       => __( 'Nombre comercial', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Inserta el nombre del comercio, proporcionado por el BPD' , 'woo_azul' ),
          'default'     => '',
          'desc_tip'    => true,
          'placeholder' => ''
        ),
        'merchantType' => array(
          'title'       => __( 'Tipo de comercio', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Inserta el tipo de comercio, proporcionado por el BPD.', 'woo_azul' ),
          'default'     => 'Comercio electronico',
          'desc_tip'    => true,
        ),
        'approvedUrl' => array(
          'title'       => __( 'Pagina de aprobacion', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Direccion de la pagina de aprobación', 'woo_azul' ),
          'default'     => get_site_url()
        ),
        'declinedUrl' => array(
          'title'       => __( 'Pagina de declinacion', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Direccion de la pagina de declinación', 'woo_azul' ),
          'default'     => get_site_url()
        ),
        'cancelUrl' => array(
          'title'       => __( 'Pagina de cancelacion', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Direccion de la pagina de cancelación', 'woo_azul' ),
          'default'     => get_site_url()
        ),
        'processUrl' => array(
          'title'       => __( 'Process Page Azul', 'woo_azul' ),
          'type'        => 'text',
          'description' => __( 'Direccion de la pagina de preparación', 'woo_azul' ),
          'default'     => get_site_url()
        )
      );

    }



    public function process_payment( $order_id ) {
      	global $woocommerce;
      	$order = new WC_Order( $order_id );
        $OrderNumber = $order_id ;
        $this->currencyCode = "$";
        $this->useCustomField1 = "1";
        $this->customFieldLabel = "orderId";
        $this->customFieldValue = $OrderNumber;
        $this->useCustomField2 = "0";
      	$baseUrl = $this->get_return_url( $order );

      	if( strpos( $baseUrl, '?') !== false ) {
      		$baseUrl .= '&';
        	} else {
        		$baseUrl .= '?';
        	 }
            $this->approvedUrl  = $this->get_option( 'approvedUrl' );// . '?wooazul=true&order_id=' . $order_id;
            $this->declinedUrl  = $this->get_option( 'declinedUrl' );// . '?wooazul=cancel&order_id=' . $order_id ;
            $this->cancelUrl  = $this->get_option( 'cancelUrl' );// . '?wooazul=cancel&order_id=' . $order_id ;

			$this->itbis = number_format($order->get_total_tax() , 2); 
			$this->itbis = str_replace(".","", $this->itbis );
			$this->itbis = str_replace(",","", $this->itbis );
			
            $this->cost = number_format($order->get_total(), 2); 
			$this->cost = str_replace(".","", $this->cost );
			$this->cost = str_replace(",","", $this->cost );

            $this->authHash = hash('sha512', $this->merchantId . $this->merchantName . $this->merchantType . $this->currencyCode .
                                               $OrderNumber . $this->cost . $this->itbis . $this->approvedUrl . $this->declinedUrl . $this->cancelUrl . $this->approvedUrl .
                                               $this->useCustomField1 . $this->customFieldLabel . $this->customFieldValue . $this->useCustomField2 . $this->authKey );
											   
            $hidden_fields = array(
                      'MerchantId' => $this->merchantId,
                      'MerchantName' => $this->merchantName,
                      'MerchantType' => $this->merchantType,
                      'CurrencyCode' => $this->currencyCode,
                      'itbis'        => $this->itbis,
                      'OrderNumber'  => $OrderNumber,
                      'Amount'       => $this->cost,
                      'ApprovedUrl'  => $this->approvedUrl,
                      'DeclinedUrl'  => $this->declinedUrl,
                      'CancelUrl'    => $this->cancelUrl,
                      'ResponsePostUrl' => $this->approvedUrl,
                      'UseCustomField1' => $this->useCustomField1,
                      'CustomField1Label' => $this->customFieldLabel,
                      'CustomField1Value' => $this->customFieldValue,
                      'UseCustomField2' => $this->useCustomField2,
                      'AuthHash' => $this->authHash,
                      'ShowTransactionResult' => 0,
                      'AzulUrl' => $this->azulUrl
                    );
            // END DATOS DE AZUL

          if($this->cost != 0){
            return array(
                			'result' => 'success',
                			'redirect' => $this->processUrl .'?'. http_build_query( $hidden_fields )
                		  );
                    }
            return array(
          			'result' => 'failure',
          			'redirect' => ''
            	);


 	      }

      }

 }
 /**
  * Add Gateway class to all payment gateway methods
  */
 function woo_add_gateway_class( $methods ) {
 	$methods[] = 'AzulPaymentGateway';
 	return $methods;
 }

 add_filter( 'woocommerce_payment_gateways', 'woo_add_gateway_class' );

 add_action( 'init', 'azulResponse' );

 function azulResponse() {
   if( isset($_GET['OrderNumber'])) {
     // Start the gateways
     WC()->payment_gateways();
     checkResponse();
   }

 }


function checkResponse(){

      global $woocommerce;

      $aUrl = '';

      if (isset($_GET['AzulUrl'])) {
        $aUrl = $_GET['AzulUrl'];
        }

      if (isset($_GET['ResponseMessage'])) {
        $ResponseMessage = $_GET['ResponseMessage'];
        }

      if (isset($_GET['OrderNumber'])) {
          $OrderNumber = $_GET['OrderNumber'];
          }
      if (isset($_GET['Amount'])) {
               $Cost = $_GET['Amount'];
        }
      if (isset($_GET['AuthorizationCode'])) {
               $AuthorizationCode = $_GET['AuthorizationCode'];
        }
      if (isset($_GET['DateTime'])) {
               $DateTime = $_GET['DateTime'];
        }
      if (isset($_GET['ResponseCode'])) {
               $ResponseCode = $_GET['ResponseCode'];
        }
      if (isset($_GET['IsoCode'])) {
               $IsoCode = $_GET['IsoCode'];
        }
      if (isset($_GET['RRN'])) {
               $RRN = $_GET['RRN'];
        }
		
	if (isset($_GET['CardNumber'])) {
              $CardNumber = $_GET['CardNumber'];
        }
		

      if (isset($_GET['AuthHash'])) {
               $hash = $_GET['AuthHash'];
            }


    	if( $aUrl == "" ) {

    		if( $OrderNumber == 0 || $OrderNumber == '' ) {
    			return;
    		  }

    		$order = new WC_Order( $OrderNumber );

        $paymentId = $order->get_transaction_id();

    		if( $order->has_status('completed') || $order->has_status('processing')) {
          return;
    		  }

			$oCost = number_format($order->get_total(), 2); 
			$oCost = str_replace(".","",$oCost );
			$oCost = str_replace(",","",$oCost );
			
          if($Cost != $oCost ){
            $order->update_status('failed', sprintf( __( '%s Pago fallido, Inconsistencia en valores, Numero de tarjeta: '. $CardNumber, 'woocommerce' ), $order->title, $paymentId ));
            return;

            }

          if($ResponseMessage != "APROBADA"){
            $order->update_status('failed', sprintf( __( '%s Pago fallido, Transaccion no aprobada. Numero de tarjeta: '. $CardNumber, 'woocommerce' ), $order->title, $paymentId ) );
            return;
            }
            $order->update_status('completed', sprintf( __( '%s Pago completado! Numero de tarjeta: <b>'. $CardNumber . '</b>Codigo Autorizacion: <b>' . $AuthorizationCode . '</b> Numero de Referencia: <b>'. $RRN.'</b>', 'woocommerce' ), $order->title, $paymentId ) );
            $woocommerce->cart->empty_cart();
            $email_class = 'WC_Email_Customer_Completed_Order';
            return array(
              	'result' => 'success',
              	'redirect' => get_site_url()
              );

    		  	if( $wooazul == 'cancel' ) {
    		  		$order = new WC_Order( $OrderNumber );
    		  		$order->update_status('cancelled', sprintf( __( '%s payment cancelled! Transaction ID: %d', 'woocommerce' ), $this->title, $paymentId ) );
    		  	}
      	}
      	return;

      }


 ?>
