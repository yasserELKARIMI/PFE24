<?php

namespace App\Options;

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
        add_action('admin_post_manual_sync_contacts', [$this, 'manualSyncContacts']);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Function to register ACF fields
    public function registerFields() {
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

    // Function to get credentials
    public static function getCredentials() {
        return [
            'bexio_token' => get_field('bexio_token', 'option'),
            'woocommerce_consumer_key' => get_field('woocommerce_consumer_key', 'option'),
            'woocommerce_consumer_secret' => get_field('woocommerce_consumer_secret', 'option'),
        ];
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
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="manual_sync_contacts">
                <?php submit_button('Manual Sync Contacts'); ?>
            </form>
        </div>
        <?php
    }

    // Function to handle manual sync orders
    public function manualSyncOrders() {
        $orderIntegration = new \App\Integration\WooCommerceBexioOrderIntegration();
        $orderIntegration->syncOrders();
        wp_redirect(admin_url('admin.php?page=bexio-integration&synced_orders=true'));
        exit;
    }

    // Function to handle manual sync products
    public function manualSyncProducts() {
        $productIntegration = new \App\Integration\WooCommerceBexioProductIntegration();
        $productIntegration->syncProducts();
        wp_redirect(admin_url('admin.php?page=bexio-integration&synced_products=true'));
        exit;
    }

    // Function to handle manual sync contacts
    public function manualSyncContacts() {
        $contactIntegration = new \App\Integration\WooCommerceBexioContactIntegration();
        $contactIntegration->syncContacts();
        wp_redirect(admin_url('admin.php?page=bexio-integration&synced_contacts=true'));
        exit;
    }
}

// Initialize the Settings class
\App\Options\Settings::getInstance();
