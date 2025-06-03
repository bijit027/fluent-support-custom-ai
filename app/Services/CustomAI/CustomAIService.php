<?php
//
//namespace FluentSupportPro\App\Services\Integrations\OpenAI;
//
//use FluentSupportPro\App\Services\Integrations\OpenAI\OpenAIHelper;
//
//class OpenAIService
//{
//    private $openAIHelper;
//
//    public function __construct(OpenAIHelper $openAIHelper)
//    {
//        $this->openAIHelper = $openAIHelper;
//    }
//
//    public function getPresetPrompts($type): array
//    {
//        return $this->openAIHelper->getPresetPrompts($type);
//    }
//
//    public function modifyResponse(string $prompt, $selectedText, $ticketId)
//    {
//        return $this->openAIHelper->modifyResponse($prompt, $selectedText, $ticketId);
//    }
//    public function generateResponse(string $responseContent, $ticket)
//    {
//        return $this->openAIHelper->generateResponse($responseContent, $ticket);
//    }
//
//    public function getTicketSummary($ticket)
//    {
//        return $this->openAIHelper->generateTicketSummary($ticket);
//    }
//
//    public function getTicketTone($ticket)
//    {
//        return $this->openAIHelper->generateTicketTone($ticket);
//    }
//}
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

    public function generateResponse(string $responseContent, $ticket)
    {
        dd('jhere');
        return (new CustomAIHelper())->generateResponse($responseContent, $ticket);
    }

    public function getTicketSummary($ticket)
    {
        return (new CustomAIHelper())->generateTicketSummary($ticket);
    }

    public function getTicketTone($ticket)
    {
        return (new CustomAIHelper())->generateTicketTone($ticket);
    }
}
