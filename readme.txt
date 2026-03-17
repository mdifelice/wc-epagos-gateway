=== ePagos Payment Gateway for WooCommerce ===
Contributors: yourusername
Tags: woocommerce, payment gateway, argentina, epagos, payments, soap, api
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Accept payments in Argentina using the ePagos payment gateway (API v2.1) with WooCommerce.

== Description ==

ePagos Payment Gateway for WooCommerce allows you to accept payments from customers in Argentina using the ePagos payment processing platform via their official SOAP API v2.1.

= Features =

* Full integration with ePagos API v2.1 (SOAP)
* Support for all Argentine payment methods available through ePagos
* Secure SOAP-based payment processing
* Test mode (Sandbox) for development and testing
* Automatic order status updates based on payment status
* Support for multiple payment methods (credit cards, debit cards, QR codes, bank transfers, cash payments)
* Compatible with WooCommerce HPOS (High-Performance Order Storage)
* Spanish (Argentina) translation included
* Detailed logging for debugging in test mode

= Supported Payment Methods =

Through ePagos, your customers can pay using:
* Credit and debit cards
* Bank transfers
* QR code payments (including Transferencias 3.0)
* Cash payments at authorized locations
* Home banking

= Requirements =

* WordPress 5.8 or higher
* WooCommerce 6.0 or higher
* PHP 7.4 or higher with SOAP extension enabled
* SSL certificate (required for production)
* Active ePagos merchant account with API credentials
* Argentine peso (ARS) as store currency

= API Integration Details =

This plugin uses ePagos API v2.1 via SOAP protocol:
* Authentication via token-based system
* Real-time payment status updates
* Secure credential management
* Support for sandbox and production environments

= Getting Started =

1. Register for an ePagos merchant account at https://www.epagos.com
2. Obtain your API credentials from the ePagos portal
3. Install and activate the plugin
4. Configure your credentials in WooCommerce settings
5. Test with sandbox credentials before going live

== Installation ==

= Automatic Installation =

1. Log in to your WordPress dashboard
2. Navigate to Plugins > Add New
3. Search for "ePagos Payment Gateway"
4. Click "Install Now" and then "Activate"

= Manual Installation =

1. Download the plugin zip file
2. Log in to your WordPress dashboard
3. Navigate to Plugins > Add New > Upload Plugin
4. Choose the zip file and click "Install Now"
5. Activate the plugin

= Configuration =

1. Navigate to WooCommerce > Settings > Payments
2. Click on "ePagos" to configure the gateway
3. Enter your API credentials from your ePagos account:
   * **Organization ID** (id_organismo) - Your organization identifier
   * **User ID** (id_usuario) - Your API user identifier
   * **Password** - Your API password
   * **Hash** - Your API hash key
   * **Convenio Number** - Your agreement number with ePagos
4. For testing:
   * Enable "Test Mode"
   * Use your sandbox credentials from https://portalsandbox.epagos.com
5. Save your settings

= Where to Get API Credentials =

1. Log in to your ePagos portal:
   * Production: https://portal.epagos.com
   * Sandbox: https://portalsandbox.epagos.com
2. Navigate to Settings > API Access
3. Copy your credentials (id_organismo, id_usuario, password, hash, convenio)

== Frequently Asked Questions ==

= Do I need an ePagos account to use this plugin? =

Yes, you need to register for an ePagos merchant account. Visit https://www.epagos.com to register.

= Does this plugin support test mode? =

Yes, the plugin includes a comprehensive test mode (sandbox) that allows you to test payments using ePagos sandbox credentials before going live.

= Which payment methods are supported? =

The plugin supports all payment methods available through ePagos, including credit cards, debit cards, bank transfers, QR codes, and cash payments at authorized locations.

= What is the Convenio number? =

The Convenio (agreement) number is a unique identifier assigned by ePagos to your merchant account. You can find it in your ePagos portal under your account settings.

= Is SSL required? =

Yes, SSL is strongly recommended for security and is required for production use to ensure secure communication with the ePagos API.

= Does this work with currencies other than ARS (Argentine Peso)? =

No, ePagos only processes payments in Argentine Pesos (ARS). Your WooCommerce store should be configured to use ARS as the currency.

= How do I enable PHP SOAP extension? =

Contact your hosting provider to enable the PHP SOAP extension. Most hosting providers have this enabled by default.

= Can I get support? =

For plugin-related issues, please use the WordPress.org support forums. For ePagos account or API issues, contact ePagos support directly.

= Is this plugin officially supported by ePagos? =

This is an independent integration. For official ePagos plugins and SDKs, visit https://github.com/epagos/sdk

== Screenshots ==

1. Payment gateway settings page
2. ePagos payment option on checkout
3. Payment confirmation page
4. Order status updates

== Changelog ==

= 1.0.0 =
* Initial release
* Full integration with ePagos API v2.1 (SOAP)
* Support for token-based authentication
* Support for payment creation via solicitud_pago method
* Support for payment status verification via obtener_pagos method
* Test mode (sandbox) support
* Spanish (Argentina) translation included
* HPOS (High-Performance Order Storage) compatibility
* Automatic order status updates based on payment state
* Support for multiple payment methods
* Detailed debug logging in test mode

== Upgrade Notice ==

= 1.0.0 =
Initial release of ePagos Payment Gateway for WooCommerce with full API v2.1 support.

== Additional Information ==

= Privacy Policy =

This plugin does not collect or store any customer data beyond what is required for payment processing. All payment data is transmitted securely to ePagos servers via SOAP protocol. Customer information (name, email, billing address) is sent to ePagos only to process the payment transaction.

= Technical Details =

* **API Version**: ePagos API v2.1
* **Protocol**: SOAP 1.2
* **Sandbox WSDL**: https://sandbox.epagos.com/wsdl/2.1/index.php?wsdl
* **Production WSDL**: https://api.epagos.com/wsdl/2.1/index.php?wsdl
* **Authentication**: Token-based (obtained via obtener_token method)

= Payment Status Codes =

The plugin handles the following ePagos payment states:
* **A** (Acreditada) - Payment approved and completed
* **P** (Pendiente) - Payment pending
* **O** (Adeudada) - Payment owed/pending
* **R** (Rechazada) - Payment rejected
* **C** (Cancelada) - Payment cancelled by user
* **V** (Vencida) - Payment expired
* **D** (Devuelto) - Payment refunded

= Developer Information =

For developers looking to extend or customize this plugin:
* GitHub Repository: [Add your repository URL]
* Issue Tracker: [Add your issue tracker URL]
* Documentation: See ePagos API reference at https://www.epagos.com/templates/desarrolladores/referencia.php

= Credits =

Developed independently for the WordPress/WooCommerce community. ePagos is a registered trademark of E-Pagos S.A.

= Legal Notice =

E-Pagos S.A. ofrece servicios de pago y no está autorizado por el Banco Central a operar como entidad financiera. Los fondos acreditados en cuentas de pago no constituyen depósitos en una entidad financiera ni están garantizados conforme legislación aplicable a depósitos en entidades financieras.
