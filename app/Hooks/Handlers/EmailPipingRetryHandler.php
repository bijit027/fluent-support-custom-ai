<?php

namespace FluentSupportPro\App\Hooks\Handlers;

use FluentSupport\Framework\Support\Arr;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Services\Helper;
use FluentSupportPro\App\Services\Integrations\FluentEmailPiping\ByMailHandler;

class EmailPipingRetryHandler
{

    private $apiUrl = 'https://email-parse-api.wpmanageninja.com/fetch_logs';

    public function register()
    {
        if (defined('FLUENTSUPPORT_ENABLE_CUSTOM_PIPE')) {
            return;
        }

        add_action('fluent_support_half_hourly', [$this, 'retryEmailLogs'], 1);
    }

    public function retryEmailLogs()
    {
        $payloadConfig = $this->getRequestConfig();

        if (!$payloadConfig) {
            return; // we don't have any email box to process
        }

        $response = wp_remote_post($this->apiUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body'    => json_encode($payloadConfig),
        ]);

        if (is_wp_error($response)) {
            return; // this is an error
        }

        $responseBody = wp_remote_retrieve_body($response);
        $responseBody = json_decode($responseBody, true);

        if (!$responseBody || Arr::get($responseBody, 'status') !== 'success') {
            return; // this is an error
        }

        $emails = Arr::get($responseBody, 'emails', []);

        $processedMimes = [];

        foreach ($emails as $email) {
            $mime = Arr::get($email, 'mime', '');
            $processedMimes[] = $mime;

            $webHookValue = $this->getBoxByToken($email['box_token']);

            if (!$webHookValue) {
                continue;
            }

            $box = MailBox::find($webHookValue->object_id);

            if (!$box) {
                continue;
            }

            ByMailHandler::processPayload(Arr::get($email, 'payload', []), $box, $mime);
        }

        Helper::updateOption('_email_piping_retry_config', [
            'last_request_date'    => Arr::get($responseBody, 'to_date', ''),
            'last_processed_mimes' => $processedMimes,
        ]);
    }

    private function getRequestConfig()
    {
        $emailBoxes = MailBox::select(['id', 'mapped_email'])
            ->where('box_type', 'email')
            ->get();

        if (!$emailBoxes) {
            return [];
        }


        $boxConfigs = [];
        foreach ($emailBoxes as $emailBox) {
            $webhookToken = $emailBox->getMeta('_webhook_token');
            if ($webhookToken && $emailBox->mapped_email) {
                $boxConfigs[$webhookToken] = $emailBox->mapped_email;
            }
        }

        if (!$boxConfigs) {
            return [];
        }

        $prevRequestConfig = Helper::getOption('_email_piping_retry_config', []);

        return array_filter([
            'site_config'     => $boxConfigs,
            'from_date'       => Arr::get($prevRequestConfig, 'last_request_date', ''),
            'processed_mimes' => Arr::get($prevRequestConfig, 'last_processed_mimes', []),
        ]);
    }

    private function getBoxByToken($token)
    {
        static $boxes = [];

        if (isset($boxes[$token])) {
            return $boxes[$token];
        }

        $box = Meta::where('key', '_webhook_token')
            ->where('value', $token)
            ->where('object_type', 'FluentSupport\App\Models\MailBox')
            ->first();
        
        if ($box) {
            $boxes[$token] = $box;
        }

        return $boxes[$token];
    }

}
