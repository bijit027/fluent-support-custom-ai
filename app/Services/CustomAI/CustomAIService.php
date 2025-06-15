<?php

namespace FluentSupportCustomAI\App\Services\CustomAI;

use FluentSupportCustomAI\App\Services\CustomAI\CustomAIHelper;

class CustomAIService
{
    public function getPresetPrompts($type): array
    {
        return (new CustomAIHelper())->getPresetPrompts($type);
    }

    public function modifyResponse(string $prompt, $selectedText, $ticketId)
    {
        return (new CustomAIHelper())->modifyResponse($prompt, $selectedText, $ticketId);
    }

    public function getTicketSummary($ticket)
    {
        return (new CustomAIHelper())->generateTicketSummary($ticket);
    }

    public function generateResponse(string $responseContent, $ticket)
    {
        return (new CustomAIHelper())->generateResponse($responseContent, $ticket);
    }

}
