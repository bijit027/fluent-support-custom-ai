<?php

namespace FluentSupportCustomAI\App\Services\CustomAI;

class CustomAIAPI
{
    protected $apiKey;

    protected $apiUrl = 'https://fluent-ai-backend.jewel-e68.workers.dev/fluent-bot/chat-completion';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function makeRequest($prompt, $ticketId, $args = [])
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $timeout = apply_filters('fs_ai_request_timeout', 60);

        $request = wp_remote_post($this->apiUrl, [
            'headers' => $headers,
            'body' => $args,
            'timeout' => $timeout,
        ]);

        $responseBody = json_decode(wp_remote_retrieve_body($request), true) ?? [];

        if (is_wp_error($request) || !$responseBody || !is_array($responseBody)) {
            return new \WP_Error('chatGPT_error', __('Invalid or empty response from API', 'fluent-support-pro'));
        }

        if (isset($responseBody['error'])) {
            $message = $responseBody['error']['message'] ?? __('Unknown error occurred', 'fluent-support-pro');
            return new \WP_Error('chatGPT_error', $message);
        }

        $statusCode = wp_remote_retrieve_response_code($request);
        if ($statusCode !== 200) {
            $error = __('Something went wrong.', 'fluent-support-pro');
            if (isset($responseBody['error']['message'])) {
                $error = __($responseBody['error']['message'], 'fluent-support-pro');
            }
            return new \WP_Error(423, $error);
        }

        $content = $responseBody['content'];

        if (empty($content)) {
            return new \WP_Error('customAI_error', __('No AI response found in the API response.', 'fluent-support-pro'));
        }

        return $content;
    }
}
