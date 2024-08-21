<?php

namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WC_Product;
use App\Options\Settings;

class WooCommerceBexioProductIntegration {
    private $bexioClient;
    private $woocommerceClient;

    public function __construct() {
        $credentials = Settings::getCredentials();
        
        // Initialize Bexio client
        $this->bexioClient = new Client([
            'base_uri' => 'https://api.bexio.com/2.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                'Accept' => 'application/json',
            ],
        ]);

        // Initialize WooCommerce client
        $this->woocommerceClient = new Client([
            'base_uri' => site_url() . '/wp-json/wc/v3/',
            'auth' => [
                $credentials['woocommerce_consumer_key'],
                $credentials['woocommerce_consumer_secret'],
            ],
        ]);
        if ($credentials['sync_method'] === 'automatic') {
            $interval = isset($credentials['sync_interval']) ? (int)$credentials['sync_interval'] * 3600 : 24 * 3600;
            if (!wp_next_scheduled('sync_woocommerce_bexio_products')) {
                wp_schedule_event(time(), 'sync_custom_interval', 'sync_woocommerce_bexio_products');
            }
        }

        // Hook for manual sync
        add_action('sync_woocommerce_bexio_products', [$this, 'syncProducts']);
    }

    public function syncProducts() {
        $products = wc_get_products(['status' => 'publish']);
        foreach ($products as $product) {
            try {
                $formattedProduct = $this->formatProductForBexio($product);
                error_log('Formatted Product: ' . print_r($formattedProduct, true));
                $response = $this->bexioClient->post('article', ['json' => $formattedProduct]);
                error_log('Bexio Response: ' . $response->getBody());
            } catch (RequestException $e) {
                error_log('Product sync error for Product ' . $product->get_id() . ': ' . $e->getMessage());
                if ($e->hasResponse()) {
                    error_log('Response: ' . $e->getResponse()->getBody()->getContents());
                }
            }
        }
    }

    protected function formatProductForBexio(WC_Product $product) {
        return [
            'user_id' => 1, // Placeholder
            'article_type_id' => 1, // Placeholder
            'contact_id' => $this->getBexioContactId($product->get_id()), // Ensure this matches a valid contact ID in Bexio
            'intern_code' => $product->get_sku(),
            'intern_name' => $product->get_name(),
            'intern_description' => $product->get_description(),
            'sale_price' => $product->get_price(),
            'sale_total' => $product->get_price(),
            'currency_id' => 8, // Add currency ID
            'is_stock' => $product->get_manage_stock(),
            'stock_nr' => $product->get_stock_quantity(),
        ];
    }

    protected function getBexioContactId($woocommerceProductId) {
        // Placeholder for retrieving Bexio contact ID based on WooCommerce product ID
        return 1;
    }

    protected function getCurrencyId($currencyCode) {
        // Implement logic to map WooCommerce currency code to Bexio currency ID
        return 1; // Placeholder
    }
}
