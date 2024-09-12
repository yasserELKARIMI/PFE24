<?php
namespace App\Options;

use GuzzleHttp\Client;
use App\Integration\WooCommerceBexioOrderIntegration;
use App\Integration\WooCommerceBexioProductIntegration;
use App\Integration\WooCommerceBexioContactIntegration;

class Settings {
    private static $instance = null;

    private function __construct() {
        // Register ACF fields
        add_action('acf/init', [$this, 'registerFields']);

        // Add Admin Menu
        add_action('admin_menu', [$this, 'addAdminMenu']);

        // Register actions for manual sync
        add_action('admin_post_manual_sync_orders', [$this, 'manualSyncOrders']);
        add_action('admin_post_manual_sync_products', [$this, 'manualSyncProducts']);

        // Schedule automatic sync if needed
        add_action('init', [$this, 'scheduleAutomaticSync']);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    // Function to register ACF fields
    public function registerFields() {
        $currencies = $this->fetchCurrenciesFromBexio();
        $taxRates = $this->fetchTaxRatesFromBexio();

        $currency_choices = [];
        foreach ($currencies as $currency) {
            $currency_choices[$currency['id']] = $currency['name'];
        }

        $tax_rate_choices = [];
        foreach ($taxRates as $taxRate) {
            $tax_rate_choices[$taxRate['id']] = $taxRate['code']; // edit by 'code' to display the VAT Code or 'name' to display the Description
        }

        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_bexio_settings',
                'title' => 'Bexio Settings',
                'fields' => [
                    [
                        'key' => 'field_bexio_token',
                        'label' => 'Bexio Token',
                        'name' => 'bexio_token',
                        'type' => 'text',
                        'instructions' => 'Enter your Bexio Token here.',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_woocommerce_consumer_key',
                        'label' => 'WooCommerce Consumer Key',
                        'name' => 'woocommerce_consumer_key',
                        'type' => 'text',
                        'instructions' => 'Enter your WooCommerce Consumer Key here.',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_woocommerce_consumer_secret',
                        'label' => 'WooCommerce Consumer Secret',
                        'name' => 'woocommerce_consumer_secret',
                        'type' => 'text',
                        'instructions' => 'Enter your WooCommerce Consumer Secret here.',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_sync_method',
                        'label' => 'Synchronization Method',
                        'name' => 'sync_method',
                        'type' => 'radio',
                        'choices' => [
                            'manual' => 'Manual',
                            'automatic' => 'Automatic',
                        ],
                        'default_value' => 'manual',
                        'layout' => 'vertical',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_sync_interval',
                        'label' => 'Sync Interval',
                        'name' => 'sync_interval',
                        'type' => 'text',
                        'instructions' => 'Enter the sync interval in hours (e.g., "24" for daily).',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_sync_method',
                                    'operator' => '==',
                                    'value' => 'automatic',
                                ],
                            ],
                        ],
                    ],

                    [
                        'key' => 'field_currency',
                        'label' => 'Currency',
                        'name' => 'bexio_currency',
                        'type' => 'select',
                        'choices' => $currency_choices,
                        'instructions' => 'Select the currency to be used.',
                        'required' => 1,
                    ],

                    [
                        'key' => 'field_tax_rate',
                        'label' => 'Tax Rate',
                        'name' => 'bexio_tax_rate',
                        'type' => 'select',
                        'choices' => $tax_rate_choices,
                        'instructions' => 'Select the tax rate to be applied.',
                        'required' => 1,
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-settings',
                        ],
                    ],
                ],
            ]);
        }
    }

    // Function to get credentials and settings
    public static function getCredentials() {
        return [
            'bexio_token' => get_field('bexio_token', 'option'),
            'woocommerce_consumer_key' => get_field('woocommerce_consumer_key', 'option'),
            'woocommerce_consumer_secret' => get_field('woocommerce_consumer_secret', 'option'),
            'sync_method' => get_field('sync_method', 'option'),
            'sync_interval' => get_field('sync_interval', 'option'),
            'bexio_currency' => get_field('bexio_currency', 'option'),
            'bexio_tax_rate' => get_field('bexio_tax_rate', 'option'),
        ];
    }

    public static function getSelectedCurrency() {
        return get_field('bexio_currency', 'option');
    }

    public static function getSelectedTaxRate() {
        return get_field('bexio_tax_rate', 'option');
    }


        // Function to fetch currencies from Bexio API
    public function fetchCurrenciesFromBexio() {
        $credentials = self::getCredentials();

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://api.bexio.com/3.0/currencies', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                    'Accept'        => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $currencies = json_decode($response->getBody(), true);
                return $currencies; // Return the list of currencies
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            error_log('Failed to fetch currencies from Bexio: ' . $e->getMessage());
            return [];
        }

        return [];
    }

    // Fetch tax rates from Bexio API
    public function fetchTaxRatesFromBexio() {
        $credentials = self::getCredentials();

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://api.bexio.com/3.0/taxes', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                    'Accept' => 'application/json',
                ],
            ]);

            $taxRates = json_decode($response->getBody(), true);
            return $taxRates;
        } catch (\Exception $e) {
            error_log('Error fetching tax rates from Bexio: ' . $e->getMessage());
            return [];
        }
    }


    // Function to add Admin Menu
    public function addAdminMenu() {
        add_menu_page(
            'WooCommerce Bexio Integration', // Page title
            'Bexio Integration', // Menu title
            'manage_options', // Capability
            'bexio-integration', // Menu slug
            [$this, 'renderAdminPage'] // Callback function
        );
    }

    // Function to render Admin Page
    public function renderAdminPage() {
        ?>
        <div class="wrap">
            <h1>WooCommerce Bexio Integration</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="manual_sync_orders">
                <?php submit_button('Manual Sync Orders'); ?>
            </form>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="manual_sync_products">
                <?php submit_button('Manual Sync Products'); ?>
            </form>
        </div>
        <?php
    }

    // Function to handle manual sync orders
    public function manualSyncOrders() {
        $contactIntegration = new WooCommerceBexioContactIntegration();
        $orderIntegration = new WooCommerceBexioOrderIntegration($contactIntegration);
        $orderIntegration->syncOrders();

        wp_redirect(admin_url('admin.php?page=bexio-integration&synced_orders=true'));
        exit;
    }

    // Function to handle manual sync products
    public function manualSyncProducts() {
        $productIntegration = new WooCommerceBexioProductIntegration();
        $productIntegration->syncProducts();

        wp_redirect(admin_url('admin.php?page=bexio-integration&synced_products=true'));
        exit;
    }

    // Function to handle automatic syncs based on timing
    public function scheduleAutomaticSync() {
        $credentials = self::getCredentials();
        if ($credentials['sync_method'] === 'automatic') {
            $interval = isset($credentials['sync_interval']) ? (int) $credentials['sync_interval'] * 3600 : 24 * 3600; // Default to 24 hours if not set
            if (!wp_next_scheduled('bexio_auto_sync')) {
                wp_schedule_event(time(), $interval, 'bexio_auto_sync');
            }
        }
    }
}

// Initialize the Settings class
\App\Options\Settings::getInstance();

// Hook the automatic synchronization event
add_action('bexio_auto_sync', function() {
    $settings = \App\Options\Settings::getInstance();

    $contactIntegration = new WooCommerceBexioContactIntegration();
    $orderIntegration = new WooCommerceBexioOrderIntegration($contactIntegration);
    $orderIntegration->syncOrders();

    $productIntegration = new WooCommerceBexioProductIntegration();
    $productIntegration->syncProducts();
});
