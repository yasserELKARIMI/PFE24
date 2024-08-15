<?php
namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WC_Product;

class WooCommerceBexioProductIntegration {
    private $bexioClient;
    private $woocommerceClient;

    public function __construct() {
        $credentials = \App\Options\Settings::getCredentials();
        $this->bexioClient = new Client([
            'base_uri' => 'https://api.bexio.com/2.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                'Accept' => 'application/json',
            ],
        ]);

        $this->woocommerceClient = new Client([
            'base_uri' => site_url('/wp-json/wc/v3/'),
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(get_option('woocommerce_api_key') . ':' . get_option('woocommerce_api_secret')),
                'Accept' => 'application/json',
            ],
        ]);

        add_action('init', [$this, 'setupScheduledTasks']);
    }

    public function setupScheduledTasks() {
        $credentials = \App\Options\Settings::getCredentials();
        $syncMethod = $credentials['sync_method'];
        $syncInterval = $credentials['sync_interval'];

        if ($syncMethod === 'automatic') {
            $intervalInSeconds = $this->convertTimeToSeconds($syncInterval);
            if (!wp_next_scheduled('sync_woocommerce_bexio_products')) {
                wp_schedule_event(time(), 'hourly', 'sync_woocommerce_bexio_products');
            }
            add_action('sync_woocommerce_bexio_products', [$this, 'syncProducts']);
        }
    }

    private function convertTimeToSeconds($time) {
        // Convert time picker format (HH:MM) to seconds
        list($hours, $minutes) = explode(':', $time);
    
        // Cast to integers
        $hours = (int) $hours;
        $minutes = (int) $minutes;
    
        return ($hours * 3600) + ($minutes * 60);
    }
    

    public function syncProducts() {
        $products = wc_get_products(['limit' => -1]);
        error_log("Found " . count($products) . " products to sync.");

        foreach ($products as $product) {
            $product_id = $product->get_id();
            error_log("Starting sync for product: $product_id");

            $productData = $this->formatProductForBexio($product);
            if ($productData) {
                $this->sendProductToBexio($product_id, $productData);
            } else {
                error_log('Invalid product data for product ' . $product_id);
            }
        }

        error_log('Product synchronization finished.');
    }

    protected function formatProductForBexio(WC_Product $product) {
        $productData = [
            'name' => $product->get_name(),
            'price' => $product->get_regular_price(),
            'description' => $product->get_description(),
            // Map other product fields as needed
        ];

        error_log('Formatted Product Data: ' . print_r($productData, true));
        return $productData;
    }

    protected function sendProductToBexio($product_id, $productData) {
        try {
            $response = $this->bexioClient->post('product', ['json' => $productData]);
            $this->handleResponse($response, $product_id);
        } catch (RequestException $e) {
            $this->handleRequestException($e, $product_id);
        }
    }

    private function handleResponse(ResponseInterface $response, $product_id) {
        $responseBody = $response->getBody()->getContents();
        error_log('Send Product Response: ' . $responseBody);

        $responseJson = json_decode($responseBody, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            error_log('Product ID in Bexio: ' . $responseJson['id']);
        } else {
            error_log('Failed to decode Bexio response for product ' . $product_id . ': ' . json_last_error_msg());
        }
    }

    private function handleRequestException(RequestException $e, $product_id) {
        error_log('Failed to send product ' . $product_id . ' to Bexio: ' . $e->getMessage());
        if ($e->hasResponse()) {
            $responseBody = Message::toString($e->getResponse());
            error_log('Response for product ' . $product_id . ': ' . $responseBody);
        }
    }
}
