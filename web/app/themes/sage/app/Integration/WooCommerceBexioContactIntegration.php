<?php

namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WP_User;
use App\Options\Settings;

class WooCommerceBexioContactIntegration {
    private $bexioClient;

    public function __construct() {
        $credentials = Settings::getCredentials();
        $this->bexioClient = new Client([
            'base_uri' => 'https://api.bexio.com/2.0/',
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
                    $response = $this->bexioClient->post('contact', ['json' => $formattedContact]);
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
                error_log('Contact sync error for User ' . $customer->ID . ': ' . $e->getMessage());
                if ($e->hasResponse()) {
                    error_log('Response: ' . $e->getResponse()->getBody()->getContents());
                }
            }
        }
        error_log('Contact synchronization finished.');
    }

    protected function getAllCustomers() {
        $users = get_users(['role' => 'customer']);
        return $users;
    }

    protected function formatContactForBexio(WP_User $user) {
        error_log('Formatting contact: ' . $user->ID);

        $contactData = [
            'contact_type_id' => 1, // Replace with actual contact type ID if needed
            'name_1' => $user->user_login, // Ensure this is the correct field
            'email' => $user->user_email,
            'user_id' => $user->ID, // Ensure user_id is included
            'owner_id' => 1 // Replace with actual owner ID if needed
        ];

        error_log('Formatted Contact Data: ' . print_r($contactData, true));
        return $contactData;
    }

    public function createOrUpdateContact(array $contactData) {
        try {
            $response = $this->bexioClient->post('contact', ['json' => $contactData]);
            $responseBody = $response->getBody()->getContents();
            error_log('Create or Update Contact Response: ' . $responseBody);

            $responseJson = json_decode($responseBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $responseJson['id'] ?? null;
            } else {
                error_log('Failed to decode contact response');
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
}
