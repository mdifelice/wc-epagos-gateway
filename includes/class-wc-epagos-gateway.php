<?php
/**
 * ePagos Payment Gateway Class - API v2.1
 *
 * @package WC_ePagos_Gateway
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_ePagos_Gateway Class
 */
class WC_ePagos_Gateway extends WC_Payment_Gateway {

    /**
     * SOAP client
     *
     * @var SoapClient
     */
    private $soap_client;

    /**
     * WSDL endpoint URL
     *
     * @var string
     */
    private $wsdl_url;

    /**
     * Constructor
     */
    public function __construct() {
        $this->id                 = 'epagos';
        $this->icon               = apply_filters( 'wc_epagos_icon', '' );
        $this->has_fields         = false;
        $this->method_title       = __( 'ePagos', 'wc-epagos-gateway' );
        $this->method_description = __( 'Accept payments via ePagos - Popular payment gateway in Argentina', 'wc-epagos-gateway' );
        $this->supports           = array(
            'products',
        );

        // Load settings
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title              = $this->get_option( 'title' );
        $this->description        = $this->get_option( 'description' );
        $this->enabled            = $this->get_option( 'enabled' );
        $this->testmode           = 'yes' === $this->get_option( 'testmode' );
        $this->id_organismo       = $this->testmode ? $this->get_option( 'test_id_organismo' ) : $this->get_option( 'id_organismo' );
        $this->id_usuario         = $this->testmode ? $this->get_option( 'test_id_usuario' ) : $this->get_option( 'id_usuario' );
        $this->password           = $this->testmode ? $this->get_option( 'test_password' ) : $this->get_option( 'password' );
        $this->hash               = $this->testmode ? $this->get_option( 'test_hash' ) : $this->get_option( 'hash' );
        $this->convenio           = $this->testmode ? $this->get_option( 'test_convenio' ) : $this->get_option( 'convenio' );
        
        // Set WSDL endpoint
        $this->wsdl_url = $this->testmode 
            ? 'https://sandbox.epagos.com/wsdl/2.1/index.php?wsdl' 
            : 'https://api.epagos.com/wsdl/2.1/index.php?wsdl';

        // Hooks
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_api_wc_epagos_gateway', array( $this, 'check_payment_status' ) );
        add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
    }

    /**
     * Initialize gateway settings form fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'wc-epagos-gateway' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable ePagos Payment Gateway', 'wc-epagos-gateway' ),
                'default' => 'no',
            ),
            'title' => array(
                'title'       => __( 'Title', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'wc-epagos-gateway' ),
                'default'     => __( 'ePagos', 'wc-epagos-gateway' ),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __( 'Description', 'wc-epagos-gateway' ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description that the customer will see during checkout.', 'wc-epagos-gateway' ),
                'default'     => __( 'Pay securely using ePagos. Available payment methods in Argentina.', 'wc-epagos-gateway' ),
                'desc_tip'    => true,
            ),
            'testmode' => array(
                'title'       => __( 'Test Mode', 'wc-epagos-gateway' ),
                'type'        => 'checkbox',
                'label'       => __( 'Enable Test Mode', 'wc-epagos-gateway' ),
                'default'     => 'yes',
                'description' => __( 'Place the payment gateway in test mode using sandbox credentials.', 'wc-epagos-gateway' ),
            ),
            'id_organismo' => array(
                'title'       => __( 'Live Organization ID', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'Get your Organization ID (id_organismo) from your ePagos account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'id_usuario' => array(
                'title'       => __( 'Live User ID', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'Get your User ID (id_usuario) from your ePagos account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'password' => array(
                'title'       => __( 'Live Password', 'wc-epagos-gateway' ),
                'type'        => 'password',
                'description' => __( 'Get your API Password from your ePagos account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'hash' => array(
                'title'       => __( 'Live Hash', 'wc-epagos-gateway' ),
                'type'        => 'password',
                'description' => __( 'Get your API Hash from your ePagos account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'convenio' => array(
                'title'       => __( 'Live Convenio Number', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'Get your Convenio number from your ePagos account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_id_organismo' => array(
                'title'       => __( 'Test Organization ID', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'Get your test Organization ID from your ePagos sandbox account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_id_usuario' => array(
                'title'       => __( 'Test User ID', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'Get your test User ID from your ePagos sandbox account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_password' => array(
                'title'       => __( 'Test Password', 'wc-epagos-gateway' ),
                'type'        => 'password',
                'description' => __( 'Get your test API Password from your ePagos sandbox account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_hash' => array(
                'title'       => __( 'Test Hash', 'wc-epagos-gateway' ),
                'type'        => 'password',
                'description' => __( 'Get your test API Hash from your ePagos sandbox account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_convenio' => array(
                'title'       => __( 'Test Convenio Number', 'wc-epagos-gateway' ),
                'type'        => 'text',
                'description' => __( 'Get your test Convenio number from your ePagos sandbox account.', 'wc-epagos-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
        );
    }

    /**
     * Get SOAP client
     *
     * @return SoapClient|WP_Error
     */
    private function get_soap_client() {
        if ( $this->soap_client ) {
            return $this->soap_client;
        }

        try {
            $this->soap_client = new SoapClient(
                $this->wsdl_url,
                array(
                    'trace'      => 1,
                    'exceptions' => true,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                )
            );
            return $this->soap_client;
        } catch ( Exception $e ) {
            $this->log( 'SOAP Error: ' . $e->getMessage() );
            return new WP_Error( 'soap_error', $e->getMessage() );
        }
    }

    /**
     * Get authentication token from ePagos API
     *
     * @return string|WP_Error Token or error
     */
    private function get_token() {
        $client = $this->get_soap_client();
        
        if ( is_wp_error( $client ) ) {
            return $client;
        }

        try {
            $params = array(
                'version'      => '2.0',
                'credenciales' => array(
                    'id_organismo' => $this->id_organismo,
                    'id_usuario'   => $this->id_usuario,
                    'password'     => $this->password,
                    'Hash'         => $this->hash,
                ),
            );

            $response = $client->obtener_token( $params );
            
            $this->log( 'Token response: ' . print_r( $response, true ) );

            if ( isset( $response->id_resp ) && '01001' === $response->id_resp ) {
                return $response->token;
            } else {
                $error_msg = isset( $response->respuesta ) ? $response->respuesta : __( 'Unknown error obtaining token', 'wc-epagos-gateway' );
                return new WP_Error( 'token_error', $error_msg );
            }
        } catch ( Exception $e ) {
            $this->log( 'Token Error: ' . $e->getMessage() );
            return new WP_Error( 'token_exception', $e->getMessage() );
        }
    }

    /**
     * Process the payment
     *
     * @param int $order_id Order ID.
     * @return array
     */
    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );

        try {
            // Get authentication token
            $token = $this->get_token();
            
            if ( is_wp_error( $token ) ) {
                throw new Exception( $token->get_error_message() );
            }

            // Create payment request
            $payment_response = $this->create_payment_request( $order, $token );

            if ( is_wp_error( $payment_response ) ) {
                throw new Exception( $payment_response->get_error_message() );
            }

            // Store transaction info
            $order->update_meta_data( '_epagos_transaction_id', $payment_response->id_transaccion );
            $order->update_meta_data( '_epagos_numero_operacion', $payment_response->numero_operacion );
            
            // Store payment URL if available
            if ( isset( $payment_response->fp[0]->url_qr ) && ! empty( $payment_response->fp[0]->url_qr ) ) {
                $order->update_meta_data( '_epagos_payment_url', $payment_response->fp[0]->url_qr );
            }
            
            $order->save();

            // Mark as pending payment
            $order->update_status( 'pending', __( 'Awaiting ePagos payment confirmation.', 'wc-epagos-gateway' ) );

            // Reduce stock
            wc_reduce_stock_levels( $order_id );

            // Remove cart
            WC()->cart->empty_cart();

            // Return success with redirect to thank you page
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url( $order ),
            );

        } catch ( Exception $e ) {
            $this->log( 'Payment Error: ' . $e->getMessage() );
            wc_add_notice( __( 'Payment error: ', 'wc-epagos-gateway' ) . $e->getMessage(), 'error' );
            return array(
                'result' => 'failure',
            );
        }
    }

    /**
     * Create payment request via SOAP API
     *
     * @param WC_Order $order Order object.
     * @param string   $token Authentication token.
     * @return object|WP_Error
     */
    private function create_payment_request( $order, $token ) {
        $client = $this->get_soap_client();
        
        if ( is_wp_error( $client ) ) {
            return $client;
        }

        try {
            // Prepare payment items
            $items = array();
            $item_id = 1;
            
            foreach ( $order->get_items() as $item ) {
                $items[] = array(
                    'id_item'       => $item_id++,
                    'desc_item'     => $item->get_name(),
                    'monto_item'    => $item->get_total(),
                    'cantidad_item' => $item->get_quantity(),
                );
            }

            // Add shipping if exists
            if ( $order->get_shipping_total() > 0 ) {
                $items[] = array(
                    'id_item'       => $item_id++,
                    'desc_item'     => __( 'Shipping', 'wc-epagos-gateway' ),
                    'monto_item'    => $order->get_shipping_total(),
                    'cantidad_item' => 1,
                );
            }

            // Prepare payer data
            $pagador = array(
                'nombre_pagador'   => $order->get_billing_first_name(),
                'apellido_pagador' => $order->get_billing_last_name(),
                'email_pagador'    => $order->get_billing_email(),
                'identificacion_pagador' => array(
                    'tipo_doc_pagador'   => 96, // DNI - adjust based on your needs
                    'numero_doc_pagador' => preg_replace( '/[^0-9]/', '', $order->get_meta( '_billing_document_number' ) ?: '0' ),
                ),
            );

            // Add phone if available
            if ( $order->get_billing_phone() ) {
                $phone = preg_replace( '/[^0-9]/', '', $order->get_billing_phone() );
                $pagador['telefono_pagador'] = array(
                    'codigo_telef_pagador' => substr( $phone, 0, 4 ),
                    'numero_telef_pagador' => substr( $phone, 4 ),
                );
            }

            // Add address if available
            if ( $order->get_billing_address_1() ) {
                $pagador['domicilio_pagador'] = array(
                    'calle_dom_pagador'     => $order->get_billing_address_1(),
                    'numero_dom_pagador'    => '',
                    'adicional_dom_pagador' => $order->get_billing_address_2(),
                    'cp_dom_pagador'        => $order->get_billing_postcode(),
                    'ciudad_dom_pagador'    => $order->get_billing_city(),
                    'provincia_dom_pagador' => 0, // Map to ePagos province codes
                    'país_dom_pagador'      => 13, // Argentina
                );
            }

            $params = array(
                'version'        => '2.0',
                'tipo_operacion' => 'op_pago',
                'credenciales'   => array(
                    'id_organismo' => $this->id_organismo,
                    'token'        => $token,
                ),
                'operacion'      => array(
                    'numero_operacion'      => $order->get_order_number(),
                    'identificador_externo_2' => $order->get_id(),
                    'id_moneda_operacion'   => 1, // ARS
                    'monto_operacion'       => $order->get_total(),
                    'detalle_operacion'     => $items,
                    'pagador'               => array( $pagador ),
                    'opc_pdf'               => false, // Don't generate PDF for faster processing
                    'opc_generar_pdf'       => false,
                ),
                'convenio'       => $this->convenio,
                'fp'             => array(
                    array(
                        'id_fp'    => 999, // All payment methods - adjust based on your configuration
                        'monto_fp' => $order->get_total(),
                    ),
                ),
            );

            $this->log( 'Payment request params: ' . print_r( $params, true ) );

            $response = $client->solicitud_pago( $params );
            
            $this->log( 'Payment response: ' . print_r( $response, true ) );

            // Check response codes: 02001 = Acreditado, 02002 = Pendiente
            if ( isset( $response->id_resp ) && in_array( $response->id_resp, array( '02001', '02002' ), true ) ) {
                return $response;
            } else {
                $error_msg = isset( $response->respuesta ) ? $response->respuesta : __( 'Payment error', 'wc-epagos-gateway' );
                return new WP_Error( 'payment_error', $error_msg );
            }

        } catch ( Exception $e ) {
            $this->log( 'SOAP Payment Error: ' . $e->getMessage() );
            return new WP_Error( 'soap_payment_error', $e->getMessage() );
        }
    }

    /**
     * Output payment instructions on thank you page
     *
     * @param int $order_id Order ID.
     */
    public function thankyou_page( $order_id ) {
        $order = wc_get_order( $order_id );
        
        if ( ! $order ) {
            return;
        }

        $payment_url = $order->get_meta( '_epagos_payment_url' );
        
        if ( $payment_url ) {
            echo '<section class="woocommerce-order-details">';
            echo '<h2 class="woocommerce-order-details__title">' . esc_html__( 'Complete your payment', 'wc-epagos-gateway' ) . '</h2>';
            echo '<p>' . esc_html__( 'Click the button below to complete your payment with ePagos.', 'wc-epagos-gateway' ) . '</p>';
            echo '<p><a href="' . esc_url( $payment_url ) . '" class="button" target="_blank">' . esc_html__( 'Pay with ePagos', 'wc-epagos-gateway' ) . '</a></p>';
            echo '</section>';
        }
    }

    /**
     * Check payment status via callback
     */
    public function check_payment_status() {
        $order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
        
        if ( ! $order_id ) {
            wp_die( 'Invalid order ID' );
        }

        $order = wc_get_order( $order_id );
        
        if ( ! $order ) {
            wp_die( 'Order not found' );
        }

        // Get payment status from ePagos
        $this->update_order_status( $order );

        wp_safe_redirect( $this->get_return_url( $order ) );
        exit;
    }

    /**
     * Update order status by checking ePagos API
     *
     * @param WC_Order $order Order object.
     */
    private function update_order_status( $order ) {
        $token = $this->get_token();
        
        if ( is_wp_error( $token ) ) {
            $this->log( 'Cannot update status - token error: ' . $token->get_error_message() );
            return;
        }

        $client = $this->get_soap_client();
        
        if ( is_wp_error( $client ) ) {
            return;
        }

        try {
            $transaction_id = $order->get_meta( '_epagos_transaction_id' );
            
            $params = array(
                'version'      => '2.0',
                'credenciales' => array(
                    'id_organismo' => $this->id_organismo,
                    'token'        => $token,
                ),
                'pago'         => array(
                    array(
                        'CodigoUnicoTransaccion' => $transaction_id,
                    ),
                ),
            );

            $response = $client->obtener_pagos( $params );
            
            $this->log( 'Payment status response: ' . print_r( $response, true ) );

            if ( isset( $response->pagos ) && is_array( $response->pagos ) && ! empty( $response->pagos ) ) {
                $pago = $response->pagos[0];
                
                switch ( $pago->Estado ) {
                    case 'A': // Acreditada
                        $order->payment_complete( $transaction_id );
                        $order->add_order_note( __( 'ePagos payment approved.', 'wc-epagos-gateway' ) );
                        break;

                    case 'R': // Rechazada
                    case 'C': // Cancelada
                        $order->update_status( 'failed', __( 'ePagos payment rejected.', 'wc-epagos-gateway' ) );
                        break;

                    case 'P': // Pendiente
                    case 'O': // Adeudada
                        $order->update_status( 'on-hold', __( 'ePagos payment pending.', 'wc-epagos-gateway' ) );
                        break;
                }
            }

        } catch ( Exception $e ) {
            $this->log( 'Status Update Error: ' . $e->getMessage() );
        }
    }

    /**
     * Log messages
     *
     * @param string $message Log message.
     */
    private function log( $message ) {
        if ( $this->testmode ) {
            $logger = wc_get_logger();
            $logger->debug( $message, array( 'source' => 'epagos' ) );
        }
    }
}
