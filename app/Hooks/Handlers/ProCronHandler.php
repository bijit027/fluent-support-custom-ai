<?php

namespace FluentSupportPro\App\Hooks\Handlers;

use FluentSupport\App\Services\Helper;
use FluentSupportPro\App\Services\ProHelper;

class ProCronHandler
{
    public function maybeUpdateStorageTokens()
    {
        $this->updateDropboxToken();
        $this->updateGoogleDriveToken();
    }

    protected function updateDropboxToken()
    {
        $dropboxSettings = Helper::getIntegrationOption('dropbox_settings', []);
        if (empty($dropboxSettings) || empty($dropboxSettings['refresh_token']) || empty($dropboxSettings['refresh_token'])) {
            return;
        }

        $url = 'https://api.dropboxapi.com/oauth2/token';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $data = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $dropboxSettings['refresh_token'],
            'client_id'     => $dropboxSettings['client_id'],
            'client_secret' => $dropboxSettings['client_secret']
        ];

        $request = wp_remote_post($url, [
            'headers' => $headers,
            'body'    => $data
        ]);
        if (is_wp_error($request)) {
            $error_message = $request->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            $request = wp_remote_retrieve_body($request);
            $request = json_decode($request, true);
        }
        if ($request) {
            $dropboxSettings['access_token'] = $request['access_token'];
            Helper::updateIntegrationOption('dropbox_settings', $dropboxSettings);
        }
    }

    protected function updateGoogleDriveToken()
    {
        $googleDriveSettings = Helper::getIntegrationOption('google_drive_settings', []);
        if (empty($googleDriveSettings) || empty($googleDriveSettings['refresh_token']) || empty($googleDriveSettings['refresh_token'])) {
            return;
        }

        $requestArgs = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body'    => json_encode([
                'client_id'     => $googleDriveSettings['client_id'],
                'client_secret' => $googleDriveSettings['client_secret'],
                'grant_type'    => 'refresh_token',
                'refresh_token' => $googleDriveSettings['refresh_token'],
            ]),
        ];

        $request = wp_remote_post('https://accounts.google.com/o/oauth2/token', $requestArgs);

        if (is_wp_error($request)) {
            return false; //
        }

        $response = wp_remote_retrieve_body($request);
        $response = json_decode($response, true);
        if ($response && isset($response['access_token'])) {
            $googleDriveSettings['access_token'] = $response['access_token'];
            Helper::updateIntegrationOption('google_drive_settings', $googleDriveSettings);
        }
    }
}
