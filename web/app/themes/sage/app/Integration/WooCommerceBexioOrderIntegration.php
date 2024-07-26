<?php

namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WC_Order;
use App\Options\Settings;

class WooCommerceBexioOrderIntegration {
    private $bexioClient;
    private $woocommerceClient;

    public function __construct() {
        $this->client = new Client([
            'timeout' => 300, // Set the timeout to 300 seconds
        ]);
        $credentials = Settings::getCredentials();
        $this->bexioClient = new Client([
            'base_uri' => 'https://api.bexio.com/2.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                'Accept' => 'application/json',
            ],
        ]);

        $this->woocommerceClient = new Client([
            'base_uri' => site_url() . '/wp-json/wc/v3/',
            'auth' => [
                $credentials['woocommerce_consumer_key'],
                $credentials['woocommerce_consumer_secret'],
            ],
        ]);

        add_action('init', function() {
            if (!wp_next_scheduled('sync_woocommerce_bexio_orders')) {
                wp_schedule_event(time(), 'every_ten_minutes', 'sync_woocommerce_bexio_orders');
            }
        });

        add_action('sync_woocommerce_bexio_orders', [$this, 'syncOrders']);
        add_action('admin_post_manual_sync_bexio_orders', [$this, 'manualSync']);
        add_action('admin_post_sync_bexio_taxes', [$this, 'syncTaxes']);
    }

    public function syncOrders() {
        error_log('Starting order synchronization...');
        $orders = wc_get_orders(['status' => 'completed']);
        error_log('Number of orders retrieved: ' . count($orders));

        foreach ($orders as $order) {
            try {
                $formattedOrder = $this->formatOrderForBexio($order);
                if ($formattedOrder) {
                    error_log('Formatted Order: ' . print_r($formattedOrder, true));
                    $response = $this->bexioClient->post('kb_offer', ['json' => $formattedOrder]);
                    $responseBody = $response->getBody()->getContents();
                    error_log('Bexio Response: ' . $responseBody);

                    error_log('Response Status: ' . $response->getStatusCode());
                    $responseJson = json_decode($responseBody, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        error_log('Decoded Bexio Response: ' . print_r($responseJson, true));
                    } else {
                        error_log('Failed to decode Bexio response');
                    }
                }
            } catch (RequestException $e) {
                error_log('Order sync error for Order ' . $order->get_id() . ': ' . $e->getMessage());
                if ($e->hasResponse()) {
                    error_log('Response: ' . $e->getResponse()->getBody()->getContents());
                }
            }
        }
        error_log('Order synchronization finished.');
    }

    public function manualSync() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $this->syncOrders();
        wp_redirect(admin_url('options-general.php?page=bexio-settings&synced=true'));
        exit;
    }

    public function syncTaxes() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $this->syncTaxesToBexio();
        wp_redirect(admin_url('options-general.php?page=bexio-settings&taxes_synced=true'));
        exit;
    }

    protected function formatOrderForBexio(WC_Order $order) {
        error_log('Formatting order: ' . $order->get_id());

        // Create or update contact with only the expected fields
        $contactData = [
            'name_1' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'contact_type_id' => 1, // Example type ID; adjust as needed
            'user_id' => 1, // Example user ID; adjust as needed
            'owner_id' => 1 // Example owner ID; adjust as needed
        ];
        $contactId = $this->createOrUpdateContact($contactData);

        if (!$contactId) {
            error_log('Invalid contact_id for order ' . $order->get_id());
            return null;
        }

        $taxIds = $this->getTaxIdsFromBexio();
        $defaultArticleId = 1;

        $positions = [];
        foreach ($order->get_items() as $item) {
            $taxClass = $item->get_tax_class() ?: 'standard';
            $taxId = $this->getMappedTaxId($taxClass, $taxIds);
            if (!$taxId) {
                error_log('Invalid tax_id for tax class ' . $taxClass);
                return null;
            }
            $positions[] = [
                'amount' => $item->get_quantity(),
                'type' => 'KbPositionArticle',
                'text' => $item->get_name(),
                'unit_price' => $item->get_total() / $item->get_quantity(),
                'discount_in_percent' => 0,
                'article_id' => $defaultArticleId,
                'tax_id' => $taxId,
            ];
        }

        $orderData = [
            'contact_id' => $contactId,
            'user_id' => 1, // Example user ID; adjust as needed
            'logopaper_id' => 1, // Example logo paper ID; adjust as needed
            'language_id' => 1, // Example language ID; adjust as needed
            'currency_id' => $this->getCurrencyId($order->get_currency()),
            'payment_type_id' => 1, // Example payment type ID; adjust as needed
            'header' => 'Thank you for your order. Here are the details:',
            'footer' => 'We hope that our service meets your expectations.',
            'is_valid_from' => $order->get_date_created()->date('Y-m-d'),
            'is_valid_until' => $order->get_date_completed() ? $order->get_date_completed()->date('Y-m-d') : '',
            'delivery_address_type' => 0,
            'api_reference' => $order->get_order_key(),
            'positions' => $positions
        ];

        error_log('Formatted Order Data: ' . print_r($orderData, true));
        return $orderData;
    }

    protected function createOrUpdateContact(array $contactData) {
        try {
            $response = $this->bexioClient->post('contact', ['json' => $contactData]);
            $responseBody = $response->getBody()->getContents();
            error_log('Create/Update Contact Response: ' . $responseBody);

            $responseJson = json_decode($responseBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return isset($responseJson['contact_id']) ? $responseJson['contact_id'] : null;
            } else {
                error_log('Failed to decode contact response: ' . json_last_error_msg());
                return null;
            }
        } catch (RequestException $e) {
            error_log('Failed to create or update contact: ' . $e->getMessage());
            if ($e->hasResponse()) {
                error_log('Response: ' . $e->getResponse()->getBody()->getContents());
            }
            return null;
        }
    }

    protected function getTaxIdsFromBexio() {
        try {
            $response = $this->bexioClient->get('taxes');
            $responseBody = $response->getBody()->getContents();
            error_log('Get Taxes Response: ' . $responseBody);

            $responseJson = json_decode($responseBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (!isset($responseJson['taxes'])) {
                    error_log('Unexpected response structure: ' . print_r($responseJson, true));
                    return [];
                }
                $taxes = $responseJson['taxes'];
                $taxIdMap = [];
                foreach ($taxes as $tax) {
                    $taxIdMap[$tax['code']] = $tax['id'];
                }
                return $taxIdMap;
            } else {
                error_log('Failed to decode taxes response: ' . json_last_error_msg());
                return [];
            }
        } catch (RequestException $e) {
            error_log('Failed to get taxes from Bexio: ' . $e->getMessage());
            if ($e->hasResponse()) {
                error_log('Response: ' . $e->getResponse()->getBody()->getContents());
            }
            return [];
        }
    }

    protected function getCurrencyId($currencyCode) {
        $currencyMap = [
            'MAD' => 1,
            'EUR' => 2,
        ];
        return isset($currencyMap[$currencyCode]) ? $currencyMap[$currencyCode] : 1;
    }

    protected function getMappedTaxId($taxClass, $taxIds) {
        return isset($taxIds[$taxClass]) ? $taxIds[$taxClass] : null;
    }

    protected function getWooCommerceTaxRates() {
        $taxRates = WC_Tax::get_rates();
        $taxRateMap = [];
        foreach ($taxRates as $taxRate) {
            $taxRateMap[$taxRate['rate_id']] = [
                'code' => $taxRate['rate_code'],
                'name' => $taxRate['rate_name'],
                'percent' => $taxRate['rate'],
            ];
        }
        return $taxRateMap;
    }

    protected function syncTaxesToBexio() {
        $taxRates = $this->getWooCommerceTaxRates();
        foreach ($taxRates as $rate) {
            $taxData = [
                'code' => $rate['code'],
                'name' => $rate['name'],
                'rate' => $rate['percent'],
            ];
            try {
                $response = $this->bexioClient->post('taxes', ['json' => $taxData]);
                $responseBody = $response->getBody()->getContents();
                error_log('Sync Tax Response: ' . $responseBody);
            } catch (RequestException $e) {
                error_log('Failed to sync tax: ' . $e->getMessage());
                if ($e->hasResponse()) {
                    error_log('Response: ' . $e->getResponse()->getBody()->getContents());
                }
            }
        }
    }
}
