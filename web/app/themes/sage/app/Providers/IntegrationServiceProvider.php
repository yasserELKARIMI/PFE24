<?php

namespace App\Providers;

use App\Options\IntegrationSettings;

class IntegrationServiceProvider
{
    protected $integrationSettings;

    public function __construct(IntegrationSettings $integrationSettings)
    {
        $this->integrationSettings = $integrationSettings;
    }

    public function getApiCredentials()
    {
        return $this->integrationSettings->getApiCredentials();
    }

    public function someIntegrationFunction()
    {
        $credentials = $this->getApiCredentials();

        $response = wp_remote_post('https://api.bexio.com/...', [
            'headers' => [
                'Authorization' => 'Bearer ' . $credentials['bexio_token'],
                'Content-Type'  => 'application/json',
            ],
            'body' => json_encode([
                // Your payload here...
            ]),
        ]);

        // Handle the response
        if (is_wp_error($response)) {
            // Handle error
        } else {
            // Process response
        }
    }
}
