<?php
namespace FluentSupportCustomAI\App\Services\CustomAI;

use FluentSupportCustomAI\App\Services\CustomAI\CustomAIAPI;
use FluentSupport\Framework\Support\Arr;

class CustomAIHelper
{
    const BASE_URL = 'https://fluent-ai-backend.jewel-e68.workers.dev/fluent-bot';
    const API_KEY = 'fak_PP8OzI9ciOeHEmznE7AqRyBMUZLNdQ8d';
    const BOT_ID = 'a9b9706b-9124-4a84-b234-3daa572dd04c';

    const ENDPOINTS = [
        'default' => '/responses',
        'ticket_reply' => '/fs-chat-completion',
    ];

    public function generateResponse($prompt, $ticket, $previousAIResponse = '')
    {
        $prompt = apply_filters('fluent_support/generate_response', $prompt, $ticket);
        $payload = [
            'ticket_conversation' => $this->getTicketMessages($ticket),
            'messages' => $this->buildMessages($previousAIResponse, $prompt),
        ];

        return $this->makeAPICall($payload, $prompt, $ticket->id, 'ticket_reply');
    }

    public function modifyResponse($prompt, $selectedText, $ticketId)
    {
        $prompt = apply_filters('fluent_support/modify_selected_text', $prompt);
        $payload = [
            'message' => "Instruction: {$prompt} Now apply this to the given text: {$selectedText}",
        ];

        return $this->makeAPICall($payload, $prompt, $ticketId);
    }

    public function generateTicketSummary($ticket)
    {
        $prompt = 'Provide a summary of the ticket from the customer\'s perspective. Each step should start with "-". Break it down into concise steps, with a maximum of 6 steps. Each step should be within 6 words per line. Use full stops for separation.';
        $prompt = apply_filters('fluent_support/generate_ticket_summary', $prompt);

        $messages = $this->getSimpleTicketMessages($ticket);
        $payload = [
            'message' => "Instruction: {$prompt} Ticket Data: " . json_encode($messages),
        ];

        return $this->makeAPICall($payload, $prompt, $ticket->id);
    }

    public function generateTicketTone($ticket)
    {
        $prompt = 'What is the tone of this ticket? Is it positive, negative, or neutral? Provide a response with a single word.';
        $prompt = apply_filters('fluent_support/find_customer_sentiment', $prompt);

        $messages = $this->getSimpleTicketMessages($ticket);
        $payload = [
            'message' => "Instruction: {$prompt} Ticket Data: " . json_encode($messages),
        ];

        return $this->makeAPICall($payload, $prompt, $ticket->id);
    }

    public function getPresetPrompts(string $type): array
    {
        if ($type === 'modifyResponse') {
            return $this->getModifyResponsePresets();
        }

        if ($type === 'createResponse') {
            return $this->getCreateResponsePresets();
        }

        return [];
    }

    private function makeAPICall(array $payload, string $prompt, int $ticketId, string $type = 'default')
    {
        $apiUrl = static::BASE_URL . static::ENDPOINTS[$type];

        $payload['botId'] = static::BOT_ID;

        $api = new CustomAIAPI(static::API_KEY, $apiUrl);
        return $api->makeRequest($ticketId, $payload, $prompt);
    }

    private function getTicketMessages($ticket): array
    {
        $messages = [];
        $ticketArray = $ticket->toArray();

        if (!empty($ticketArray['content'])) {
            $messages[] = [
                'role' => 'Customer',
                'message' => $this->cleanText($ticketArray['content']),
            ];
        }

        foreach (Arr::get($ticketArray, 'responses', []) as $response) {
            $role = Arr::get($response, 'person.person_type') === 'customer' ? 'Customer' : 'Support Agent';
            $messages[] = [
                'role' => $role,
                'message' => $this->cleanText(Arr::get($response, 'content', '')),
            ];
        }

        return $messages;
    }

    private function getSimpleTicketMessages($ticket): array
    {
        $messages = [];
        $ticketArray = $ticket->toArray();

        if (!empty($ticketArray['content'])) {
            $messages[] = [
                'role' => 'human',
                'message' => $this->cleanText($ticketArray['content']),
            ];
        }

        foreach (Arr::get($ticketArray, 'responses', []) as $response) {
            $role = Arr::get($response, 'person.person_type') === 'customer' ? 'human' : 'ai';
            $messages[] = [
                'role' => $role,
                'message' => $this->cleanText(Arr::get($response, 'content', '')),
            ];
        }

        return $messages;
    }

    private function buildMessages(string $previousAIResponse = '', string $prompt = ''): array
    {
        $messages = [];

        if ($previousAIResponse) {
            $messages[] = ['role' => 'ai', 'message' => $previousAIResponse];
        }

        if ($prompt) {
            $messages[] = ['role' => 'human', 'message' => $prompt];
        }

        return $messages;
    }

    private function cleanText(string $text): string
    {
        return trim(strip_tags($text));
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
