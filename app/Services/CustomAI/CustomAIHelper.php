<?php

namespace FluentSupportCustomAI\App\Services\CustomAI;

use FluentSupportCustomAI\App\Services\CustomAI\CustomAIAPI;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Models\AIActivityLogs;
use FluentSupport\Framework\Support\Arr;

class CustomAIHelper
{
    const BASE_URL = 'https://fluent-ai-backend.jewel-e68.workers.dev/fluent-bot';

    const ENDPOINTS = [
        'default' => '/responses',
        'ticket_reply' => '/chat-completion',
    ];

    public function generateResponse($prompt, $ticket)
    {
        $prompt = apply_filters('fluent_support/generate_response', $prompt, $ticket);
        $ticketData = $this->prepareTicketData($ticket);
        return $this->callAPI($ticketData, $prompt, $ticket->id, 'ticket_reply');
    }

    public function modifyResponse($prompt, $selectedText, $ticketId)
    {
        $prompt = apply_filters('fluent_support/modify_selected_text', $prompt);
        $query = "Instruction: {$prompt} Now apply this to the selected text: \"{$selectedText}\".";

        return $this->callAPI([], $query, $ticketId);
    }

    public function generateTicketSummary($ticket)
    {
        $prompt = 'Provide a summary of the ticket from the customer\'s perspective. Each step should start with "-". Break it down into concise steps, with a maximum of 6 steps. Each step should be within 6 words per line. Use full stops for separation.';
        $prompt = apply_filters('fluent_support/generate_ticket_summary', $prompt);
        $ticketData = $this->prepareTicketData($ticket);

        $query = "Instruction: {$prompt} Ticket Data: \"" . json_encode(Arr::get($ticketData, 'messages', [])) . "\".";

        return $this->callAPI([], $query, $ticket->id);
    }

    public function generateTicketTone($ticket)
    {
        $prompt = 'What is the tone of this ticket? Is it positive, negative, or neutral? Provide a response with a single word.';
        $prompt = apply_filters('fluent_support/find_customer_sentiment', $prompt);
        $ticketData = $this->prepareTicketData($ticket);

        $query = "Instruction: {$prompt} Ticket Data: \"" . json_encode(Arr::get($ticketData, 'messages', [])) . "\".";

        return $this->callAPI([], $query, $ticket->id);
    }

    public function getPresetPrompts(string $type): array
    {
        $presets = [
            'modifyResponse' => [$this, 'getModifyResponsePresets'],
            'createResponse' => [$this, 'getCreateResponsePresets'],
        ];

        return isset($presets[$type]) ? call_user_func($presets[$type]) : [];
    }

    private function callAPI(array $ticketData, string $prompt, int $ticketId, string $type = 'default')
    {
        $config = $this->getAPIConfig($type);
        $apiUrl = self::BASE_URL . self::ENDPOINTS[$type];
        $payload = $this->buildPayload($type, $ticketData, $prompt, $config['bot_id']);

        $api = new CustomAIAPI($config['api_key'], $apiUrl);
        return $api->makeRequest($ticketId, $payload);
    }

    private function buildPayload(string $type, array $ticketData, string $prompt, string $botId): array
    {
        $promptKey = $type === 'ticket_reply' ? 'additionalPrompt' : 'message';

        // Use Arr::only to get 'botId' from a temporary array for consistency
        return array_merge($ticketData, ['botId' => $botId, $promptKey => $prompt]);
    }

    private function getAPIConfig(string $type = 'default'): array
    {
        $configurations = [
            'default' => [
                'api_key' => 'fak_PP8OzI9ciOeHEmznE7AqRyBMUZLNdQ8d',
                'bot_id' => 'a9b9706b-9124-4a84-b234-3daa572dd04c',
            ],
        ];

        return Arr::get($configurations, $type, $configurations['default']);
    }

    private function prepareTicketData($ticket): array
    {
        $ticketArray = $ticket->toArray();
        return $this->formatTicketData($ticketArray);
    }

    private function formatTicketData($ticket): array
    {
        $messages = [];

        // Additional ticket content as the first human message
        if (!empty(Arr::get($ticket, 'content'))) {
            $messages[] = [
                'role' => 'human',
                'content' => Arr::get($ticket, 'content'),
            ];
        }

        // Added replies
        foreach (Arr::get($ticket, 'responses', []) as $response) {
            $messages[] = [
                'role' => Arr::get($response, 'person.person_type') === 'customer' ? 'human' : 'ai',
                'content' => Arr::get($response, 'content', ''),
            ];
        }

        return ['messages' => $messages];
    }

    private function getModifyResponsePresets(): array
    {
        $presets = [
            [
                'label' => 'Improve Writing',
                'text' => 'shorten',
                'description' => 'Use AI to refine the text by removing unnecessary words and making it more concise while retaining the original meaning and key information.'
            ],
            [
                'label' => 'Fix Spelling & Grammar',
                'text' => 'lengthen',
                'description' => 'Apply AI to correct any spelling and grammatical errors in the text, ensuring it is free of mistakes and reads professionally.'
            ],
            [
                'label' => 'Make Shorter',
                'text' => 'friendly',
                'description' => 'AI will modify the text to make it shorter and more casual, making it suitable for informal or friendly communication.'
            ],
            [
                'label' => 'Make Longer',
                'text' => 'professional',
                'description' => 'Enhance the text by adding more details and using refined language to make it more formal and detailed, appropriate for professional settings.'
            ],
            [
                'label' => 'Simplify Language',
                'text' => 'simplify',
                'description' => 'Utilize AI to simplify complex phrases and terminology, making the text easier to read and understand for a general audience.'
            ]
        ];

        return apply_filters('fluent_support/get_modify_response_preset_prompts', $presets);
    }

    private function getCreateResponsePresets(): array
    {
        $presets = [
            [
                'label' => 'Request More Information',
                'text' => 'requestInfo',
                'description' => 'Ask the customer to provide additional details or clarification about the issue they reported. This helps in gathering more information to resolve the issue effectively.'
            ],
            [
                'label' => 'Acknowledge Issue',
                'text' => 'acknowledgeIssue',
                'description' => 'Confirm receipt of the customer\'s issue and reassure them that it is being investigated. This demonstrates that their concern is being taken seriously.'
            ],
            [
                'label' => 'Provide Solution',
                'text' => 'provideSolution',
                'description' => 'Offer a comprehensive solution or resolution to the problem described by the customer. This should address their concerns and provide actionable steps to resolve the issue.'
            ],
            [
                'label' => 'Follow Up',
                'text' => 'followUp',
                'description' => 'Reach out to the customer after a solution has been provided to ensure that their issue has been resolved to their satisfaction. This helps in confirming the resolution and maintaining good customer relations.'
            ],
            [
                'label' => 'Close Ticket',
                'text' => 'closeTicket',
                'description' => 'Notify the customer that their ticket will be closed as the issue has been resolved. Ensure that all their concerns are addressed before closing the ticket.'
            ]
        ];

        return apply_filters('fluent_support/get_create_response_preset_prompts', $presets);
    }
}
