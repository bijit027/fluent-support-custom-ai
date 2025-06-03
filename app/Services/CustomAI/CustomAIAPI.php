<?php
//
//namespace FluentSupportCustomAI\App\Services\CustomAI;
//
//class CustomAIAPI
//{
//    protected $apiKey;
//
//    protected $model = 'gpt-3.5-turbo';
//
//    protected $modelUrl = 'https://fluent-ai-backend.jewel-e68.workers.dev/fluent-bot/chat-completion';
//
//    public function __construct($apiKey, $model = '')
//    {
//        $this->apiKey = 'fak_PP8OzI9ciOeHEmznE7AqRyBMUZLNdQ8d';
//
//        if ($model) {
//            $this->model = $model;
//        }
//    }
//
//    public function makeRequest($prompt, $ticketId, $args = [])
//    {
//
//        $headers = [
//            'Authorization' => 'Bearer ' . $this->apiKey,
//            'Content-Type'  => 'application/json',
//        ];
//
//        $timeout = apply_filters('fs_ai_request_timeout', 60);
//
//        $request = wp_remote_post($this->modelUrl, [
//            'headers' => $headers,
//            'body'    => $args,
//            'timeout' => $timeout,
//        ]);
//        $body = wp_remote_retrieve_body($request);
//        $body = json_decode($body, true);
//
//        if (!$body || !is_array($body)) {
//            return new \WP_Error('chatGPT_error', __('Invalid or empty response from API', 'fluent-support-pro'));
//        }
//
//        if (isset($body['error'])) {
//            $message = $body['error']['message'] ?? __('Unknown error occurred', 'fluent-support-pro');
//            return new \WP_Error('chatGPT_error', $message);
//        }
//
//        $code = wp_remote_retrieve_response_code($request);
//        if ($code !== 200) {
//            $error = __('Something went wrong.', 'fluent-support-pro');
//            if (isset($body['error']['message'])) {
//                $error = __($body['error']['message'], 'fluent-support-pro');
//            }
//            return new \WP_Error(423, $error);
//        }
//
//// Retrieve the last AI message content from 'messages' array
//        $lastAiContent = '';
//        if (!empty($body['messages']) && is_array($body['messages'])) {
//            // Reverse loop to find last AI role message
//            for ($i = count($body['messages']) - 1; $i >= 0; $i--) {
//                if (isset($body['messages'][$i]['role']) && $body['messages'][$i]['role'] === 'ai') {
//                    $lastAiContent = $body['messages'][$i]['content'] ?? '';
//                    break;
//                }
//            }
//        }
//
//        if (empty($lastAiContent)) {
//            return new \WP_Error('chatGPT_error', __('No AI response found in the API response.', 'fluent-support-pro'));
//        }
//
//        do_action('fluent_support/open_ai_response_success', $ticketId, $prompt, $usedTokens ?? 0, $body, $this->model);
//
//        return $lastAiContent;
//    }
//}


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

        $responseBody = wp_remote_retrieve_body($request);
        $responseBody = json_decode($responseBody, true);

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

        $lastAiContent = '';
        if (!empty($responseBody['messages']) && is_array($responseBody['messages'])) {
            for ($i = count($responseBody['messages']) - 1; $i >= 0; $i--) {
                if (isset($responseBody['messages'][$i]['role']) && $responseBody['messages'][$i]['role'] === 'ai') {
                    $lastAiContent = $responseBody['messages'][$i]['content'] ?? '';
                    break;
                }
            }
        }

        if (empty($lastAiContent)) {
            return new \WP_Error('customAI_error', __('No AI response found in the API response.', 'fluent-support-pro'));
        }

        do_action('fluent_support/open_ai_response_success', $ticketId, $prompt, 0, $responseBody, 'custom_ai');

        return $lastAiContent;
    }
}
