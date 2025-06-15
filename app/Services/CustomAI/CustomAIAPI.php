<?php

namespace FluentSupportCustomAI\App\Services\CustomAI;

use WP_Error;

class CustomAIAPI
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    public function makeRequest(int $ticketId, array $args = [])
    {
        $response = $this->sendRequest($args);

        if (is_wp_error($response)) {
            return new WP_Error('chatGPT_error', __('Invalid or empty response from API', 'fluent-support-custom-ai'));
        }

        $responseBody = json_decode(wp_remote_retrieve_body($response), true) ?? [];

        if (!$responseBody || !is_array($responseBody)) {
            return new WP_Error('chatGPT_error', __('Invalid or empty response from API', 'fluent-support-custom-ai'));
        }

        if (!empty($responseBody['error'])) {
            $message = $responseBody['error']['message'] ?? __('Unknown error occurred', 'fluent-support-custom-ai');
            return new WP_Error('chatGPT_error', $message);
        }

        $statusCode = wp_remote_retrieve_response_code($response);
        if ($statusCode !== 200) {
            $error = $responseBody['error']['message'] ?? __('Something went wrong.', 'fluent-support-custom-ai');
            return new WP_Error(423, __($error, 'fluent-support-custom-ai'));
        }

        $content = $responseBody['content'] ?? null;

        if (empty($content)) {
            return new WP_Error('customAI_error', __('No AI response found in the API response.', 'fluent-support-custom-ai'));
        }

        return $content;
    }

    protected function sendRequest(array $payload)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ];

        $timeout = apply_filters('fs_ai_request_timeout', 60);

        return wp_remote_post($this->apiUrl, [
            'headers' => $headers,
            'body'    => wp_json_encode($payload),
            'timeout' => $timeout,
        ]);
    }
}
