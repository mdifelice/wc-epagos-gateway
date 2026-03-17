=== ePagos Payment Gateway for WooCommerce ===
Contributors: yourusername
Tags: woocommerce, payment gateway, argentina, epagos, payments
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Accept payments in Argentina using the ePagos payment gateway with WooCommerce.

== Description ==

ePagos Payment Gateway for WooCommerce allows you to accept payments from customers in Argentina using the ePagos payment processing platform.

= Features =

* Easy integration with WooCommerce
* Support for Argentine payment methods
* Secure payment processing
* Test mode for development and testing
* Automatic order status updates via webhooks
* Support for refunds directly from WooCommerce
* Compatible with WooCommerce HPOS (High-Performance Order Storage)

= Requirements =

* WordPress 5.8 or higher
* WooCommerce 6.0 or higher
* PHP 7.4 or higher
* SSL certificate (required for production)
* ePagos merchant account

= Getting Started =

1. Install and activate the plugin
2. Go to WooCommerce > Settings > Payments
3. Enable ePagos and click "Manage"
4. Enter your ePagos API credentials
5. Configure the webhook URL in your ePagos account dashboard
6. Save your settings and test the integration

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
   * Merchant ID
   * API Key
   * Secret Key
4. Configure the webhook URL in your ePagos dashboard:
   * Copy the webhook URL shown in the settings
   * Add it to your ePagos account notifications settings
5. Enable test mode to test payments before going live
6. Save your settings

== Frequently Asked Questions ==

= Do I need an ePagos account to use this plugin? =

Yes, you need to register for an ePagos merchant account at https://www.epagos.com.ar

= Does this plugin support test mode? =

Yes, the plugin includes a test mode that allows you to test payments using ePagos sandbox credentials.

= Which payment methods are supported? =

The plugin supports all payment methods available through ePagos, including credit cards, debit cards, and other Argentine payment methods.

= Can I process refunds? =

Yes, you can process full or partial refunds directly from the WooCommerce order page.

= Is SSL required? =

SSL is strongly recommended for security and is required for production use.

= Where do I get API credentials? =

Log in to your ePagos merchant dashboard to find your API credentials under Settings > API Access.

= How do I configure webhooks? =

The webhook URL is displayed in the plugin settings. Copy this URL and add it to your ePagos account under Settings > Webhooks/Notifications.

== Screenshots ==

1. Payment gateway settings page
2. ePagos payment option on checkout
3. Order received page after successful payment

== Changelog ==

= 1.0.0 =
* Initial release
* Support for payments via ePagos
* Test mode support
* Webhook integration
* Refund support
* HPOS compatibility

== Upgrade Notice ==

= 1.0.0 =
Initial release of ePagos Payment Gateway for WooCommerce.

== Additional Information ==

For support, documentation, and feature requests, please visit the plugin's GitHub repository or contact support through the WordPress.org forums.

= Privacy Policy =

This plugin does not collect or store any customer data beyond what is required for payment processing. All payment data is transmitted securely to ePagos servers.

= Credits =

Developed by [Your Name/Company]
