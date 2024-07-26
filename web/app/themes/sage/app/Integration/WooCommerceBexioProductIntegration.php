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

        // Schedule the events with a 10-minute interval
        add_action('init', function() {
            if (!wp_next_scheduled('sync_woocommerce_bexio_products')) {
                wp_schedule_event(time(), 'every_ten_minutes', 'sync_woocommerce_bexio_products');
            }
        });

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
            'deliverer_code' => null,
            'deliverer_name' => null,
            'deliverer_description' => null,
            'intern_code' => $product->get_sku(),
            'intern_name' => $product->get_name(),
            'intern_description' => $product->get_description(),
            'purchase_price' => null,
            'sale_price' => $product->get_price(),
            'purchase_total' => null,
            'sale_total' => $product->get_price(),
            'currency_id' => $this->getCurrencyId(get_woocommerce_currency()), // Add currency ID
            'unit_id' => null,
            'is_stock' => $product->get_manage_stock(),
            'stock_nr' => $product->get_stock_quantity(),
            // Removed the unexpected fields
        ];
    }
    

    protected function getBexioContactId($woocommerceProductId) {
        // Implement the logic to retrieve Bexio contact ID based on WooCommerce product ID
        // This could involve querying a database or calling another API endpoint
        // For now, we'll use a placeholder
        return 1; // Placeholder
    }

    protected function getCurrencyId($currencyCode) {
        $currencyMap = [
            'USD' => 1,
            'EUR' => 2,
            // Add other currencies as needed
        ];
        return isset($currencyMap[$currencyCode]) ? $currencyMap[$currencyCode] : null;
    }
}
