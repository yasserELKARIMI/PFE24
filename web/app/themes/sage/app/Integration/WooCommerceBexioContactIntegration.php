<?php
namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WP_User;

class WooCommerceBexioContactIntegration {
    private $bexioClient;

    public function __construct() {
        $credentials = \App\Options\Settings::getCredentials();
        $this->bexioClient = new Client([
            'base_uri' => 'https://api.bexio.com/3.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function syncContacts() {
        error_log('Starting contact synchronization...');
        $customers = $this->getAllCustomers();
        foreach ($customers as $customer) {
            try {
                $formattedContact = $this->formatContactForBexio($customer);
                if ($formattedContact) {
                    error_log('Formatted Contact: ' . print_r($formattedContact, true));
                    $contactId = $this->createOrUpdateContact($formattedContact);
                    if ($contactId) {
                        error_log('Contact synced successfully: ' . $contactId);
                    } else {
                        error_log('Failed to sync contact for User ' . $customer->ID);
                    }
                }
            } catch (RequestException $e) {
                error_log('Contact sync error for User ' . $customer->ID . ': ' . $e->getMessage());
                if ($e->hasResponse()) {
                    error_log('Response: ' . $e->getResponse()->getBody()->getContents());
                }
            }
        }
        error_log('Contact synchronization finished.');
    }

    protected function getAllCustomers() {
        return get_users(['role' => 'customer']);
    }

    public function formatContactForBexio(WP_User $user) {
        $billingAddress = get_user_meta($user->ID, 'billing_address_1', true);
        $billingPostcode = get_user_meta($user->ID, 'billing_postcode', true);
        $billingCity = get_user_meta($user->ID, 'billing_city', true);
        $billingCountry = get_user_meta($user->ID, 'billing_country', true);
        $email = $user->user_email;

        $validCountryId = $this->getValidCountryId($billingCountry);

        return [
            'nr' => null,
            'contact_type_id' => 1,
            'name_1' => $user->display_name ?: 'Unknown Name',
            'address' => $billingAddress ?: 'Unknown Address',
            'postcode' => $billingPostcode ?: 'Unknown Postcode',
            'city' => $billingCity ?: 'Unknown City',
            'country_id' => $validCountryId,
            'mail' => $email ?: 'Unknown Email',
            'user_id' => 1, // Ensure user_id is correct
            'owner_id' => 1, // Example owner_id, adjust as needed
        ];
    }

    private function getValidCountryId($countryCode) {
        // Fetch valid country ID based on country code or use a mapping
        $countryMapping = [
            'GN' => 1, // Example mapping, adjust according to your Bexio configuration
            'DM' => 2, // Example mapping
        ];
        return $countryMapping[$countryCode] ?? null;
    }

    protected function createOrUpdateContact(array $contactData) {
        try {
            $response = $this->bexioClient->post('contact', ['json' => $contactData]);
            $responseBody = $response->getBody()->getContents();
            error_log('Create/Update Contact Response: ' . $responseBody);

            $responseJson = json_decode($responseBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $responseJson['id'] ?? null;
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

    public function publicCreateOrUpdateContact(array $contactData) {
        return $this->createOrUpdateContact($contactData);
    }
}
