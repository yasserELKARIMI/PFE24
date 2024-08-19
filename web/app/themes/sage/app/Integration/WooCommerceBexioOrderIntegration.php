<?php
namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WC_Order;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Message;

class WooCommerceBexioOrderIntegration {
    private $bexioClient;
    private $woocommerceClient;
    private $contactIntegration;

    public function __construct(WooCommerceBexioContactIntegration $contactIntegration) {
        if (!$contactIntegration) {
            error_log('Contact integration not passed to WooCommerceBexioOrderIntegration');
            return;
        }

        $this->contactIntegration = $contactIntegration;

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

        if ($credentials['sync_method'] === 'automatic') {
            $interval = isset($credentials['sync_interval']) ? (int)$credentials['sync_interval'] * 3600 : 24 * 3600;
            if (!wp_next_scheduled('sync_woocommerce_bexio_orders')) {
                wp_schedule_event(time(), 'sync_custom_interval', 'sync_woocommerce_bexio_orders');
            }
        }

        // Hook for manual sync
        add_action('sync_woocommerce_bexio_orders', [$this, 'syncOrders']);
    }

    public function setupScheduledTasks() {
        if (!wp_next_scheduled('sync_woocommerce_bexio_orders')) {
            wp_schedule_event(time(), 'hourly', 'sync_woocommerce_bexio_orders');
        }
        add_action('sync_woocommerce_bexio_orders', [$this, 'syncOrders']);
    }

    public function syncOrders() {
        $orders = wc_get_orders(['limit' => -1]);
        error_log("Found " . count($orders) . " orders to sync.");

        foreach ($orders as $order) {
            $order_id = $order->get_id();
            error_log("Starting sync for order: $order_id");

            $contact_id = $this->getContactIdForOrder($order);
            if (!$contact_id) {
                error_log("Skipping order $order_id due to missing contact ID.");
                continue;
            }

            $orderData = $this->formatOrderForBexio($order, $contact_id);
            if ($orderData) {
                $this->sendOrderToBexio($order_id, $orderData);
            } else {
                error_log('Invalid order data for order ' . $order_id);
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

    protected function getContactIdForOrder(WC_Order $order) {
        $customer_id = $order->get_customer_id();
        if ($customer_id) {
            $customer = get_user_by('ID', $customer_id);
            if ($customer instanceof \WP_User) {
                if ($this->contactIntegration) {
                    $contact_id = $this->contactIntegration->publicCreateOrUpdateContact($this->contactIntegration->formatContactForBexio($customer));
                    if ($contact_id) {
                        return $contact_id;
                    }
                    error_log("Failed to create or update contact for customer {$customer_id}");
                } else {
                    error_log("Contact integration is not initialized.");
                }
            } else {
                error_log("Customer with ID {$customer_id} is not a WP_User.");
            }
        } else {
            error_log("No customer ID found in order.");
        }
        return null;
    }

    protected function formatOrderForBexio(WC_Order $order, $contactId) {
        if (!$contactId) {
            error_log('No contact ID provided for order ' . $order->get_id());
            return null;
        }

        $positions = [];
        foreach ($order->get_items() as $item) {
            $unitPrice = $item->get_total() / $item->get_quantity();

            $positions[] = [
                'type' => 'KbPositionCustom',
                'text' => $item->get_name(),
                'tax_id' => 3, // Ensure this matches Bexio's tax ID
                'amount' => $item->get_quantity(),
                'unit_price' => $unitPrice,
            ];
        }

        $orderData = [
            'contact_id' => $contactId,
            'title' => $order->get_id(),
            'is_valid_from' => $order->get_date_created()->date('Y-m-d'),
            'user_id' => 1, // Or fetch the correct user ID
            'currency_id' => 8, // Assuming MAD as the currency ID
            'positions' => $positions,
        ];

        error_log('Formatted Order Data: ' . print_r($orderData, true));
        return $orderData;
    }

    protected function sendOrderToBexio($order_id, $orderData) {
        try {
            $response = $this->bexioClient->post('kb_order', ['json' => $orderData]);
            $this->handleResponse($response, $order_id);
        } catch (RequestException $e) {
            $this->handleRequestException($e, $order_id);
        }
    }

    private function handleResponse(ResponseInterface $response, $order_id) {
        $responseBody = $response->getBody()->getContents();
        error_log('Send Order Response: ' . $responseBody);

        $responseJson = json_decode($responseBody, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            error_log('Order ID in Bexio: ' . $responseJson['id']);
        } else {
            error_log('Failed to decode Bexio response for order ' . $order_id . ': ' . json_last_error_msg());
        }
    }

    private function handleRequestException(RequestException $e, $order_id) {
        error_log('Failed to send order ' . $order_id . ' to Bexio: ' . $e->getMessage());
        if ($e->hasResponse()) {
            $responseBody = Message::toString($e->getResponse());
            error_log('Response for order ' . $order_id . ': ' . $responseBody);
        }
    }
}
