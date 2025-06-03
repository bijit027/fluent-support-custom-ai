<?php

namespace FluentSupportPro\App\Hooks\Handlers;

use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\Framework\Support\Str;
use FluentSupportPro\App\Models\TimeTrack;
use FluentSupport\App\Services\Helper;
use FluentSupportPro\App\Services\ProHelper;
use FluentSupport\App\Services\Csv\CsvWriter;

class DataExporter
{
    private $request;

    public function exportTicketsTimeSheet()
    {
        $this->verifyRequest();
        $request = Helper::FluentSupport('request');

        $selectedMailBoxIDs = $request->get('mailbox_id');

        $rawDateRange = explode(',', $request->get('dateRange', ''));
        $dateRange = ProHelper::getValidatedDateRange($rawDateRange);

        $mailBoxIDs = ( $selectedMailBoxIDs != null) ? array_map('intval', explode(',', $selectedMailBoxIDs)) : null;

        $tracks = TimeTrack::when($mailBoxIDs, function ($q) use ($mailBoxIDs) {
            $q->whereIn('mailbox_id', $mailBoxIDs);
        })
            ->orderBy('updated_at', 'DESC')
            ->whereBetween('completed_at', $dateRange)
            ->with(['agent', 'mailBox', 'ticket' => function ($q) {
                $q->select('id', 'title', 'slug');
            }])
            ->whereHas('ticket')
            ->get();

        $writer = new CsvWriter();

        $writer->insertOne([
            'Ticket',
            'MailBox',
            'Agent',
            'Log Date',
            'Billable Hours',
            'Notes'
        ]);

        $rows = [];

        foreach ($tracks as $track) {
            $rows[] = [
                $this->sanitizeForCSV($track->ticket->title),
                $this->sanitizeForCSV($track->mailBox->name),
                $this->sanitizeForCSV($track->agent->full_name),
                $this->formatTime($track->completed_at, 'Y-m-d'),
                $this->miniutesToHours($track->billable_minutes),
                $this->sanitizeForCSV($track->message)
            ];
        }

        $writer->insertAll($rows);
        $writer->output('time-sheet-' . date('Y-m-d_H-i') . '.csv');
        die();
    }

    public function exportCustomersTimeSheet()
    {
        $this->verifyRequest();
        $request = Helper::FluentSupport('request');

        $selectedCustomers = $request->get('selectedCustomers');

        $rawDateRange = explode(',', $request->get('dateRange', ''));
        $dateRange = ProHelper::getValidatedDateRange($rawDateRange);

        $customerIds = ($selectedCustomers != 'null') ? array_map('intval', explode(',', $selectedCustomers)) : null;

        $tracks = TimeTrack::when($customerIds, function ($query) use ($customerIds) {
            $query->whereIn('customer_id', $customerIds);
        })
            ->whereBetween('completed_at', $dateRange)
            ->whereHas('ticket')
            ->with([
                'agent',
                'customer',
                'ticket' => function ($query) {
                    $query->select('id', 'title', 'slug');
                }
            ])
            ->orderBy('updated_at', 'DESC')
            ->get();

        $writer = new CsvWriter();

        $writer->insertOne([
            'Customer',
            'Ticket',
            'Agent',
            'Log Date',
            'Billable Hours',
            'Notes'
        ]);

        $rows = [];

        foreach ($tracks as $track) {
            $rows[] = [
                $this->sanitizeForCSV($track->customer->full_name),
                $this->sanitizeForCSV($track->ticket->title),
                $this->sanitizeForCSV($track->agent->full_name),
                $this->formatTime($track->completed_at, 'Y-m-d'),
                $this->miniutesToHours($track->billable_minutes),
                $this->sanitizeForCSV($track->message)
            ];
        }

        $writer->insertAll($rows);
        $writer->output('time-sheet-' . date('Y-m-d_H-i') . '.csv');
        die();
    }
    public function exportAgentsTimeSheet()
    {
        $this->verifyRequest();
        $request = Helper::FluentSupport('request');

        $selectedAgents= $request->get('selectedAgents');

        $rawDateRange = explode(',', $request->get('dateRange', ''));
        $dateRange = ProHelper::getValidatedDateRange($rawDateRange);

        $agentIds =  ($selectedAgents !== 'null') ? array_map('intval', explode(',', $selectedAgents)) : null;

        $tracks = TimeTrack::when(!empty($agentIds), function ($q) use ($agentIds) {
            $q->whereIn('agent_id', $agentIds);
        })
            ->orderBy('updated_at', 'DESC')
            ->whereBetween('completed_at', $dateRange)
            ->with(['agent', 'ticket' => function ($q) {
                $q->select('id', 'title', 'slug');
            }])
            ->whereHas('ticket')
            ->get();

        $writer = new CsvWriter();

        $writer->insertOne([
            'Agent',
            'Ticket',
            'Log Date',
            'Billable Hours',
            'Notes'
        ]);

        $rows = [];

        foreach ($tracks as $track) {
            $rows[] = [
                $this->sanitizeForCSV($track->agent->full_name),
                $this->sanitizeForCSV($track->ticket->title),
                $this->formatTime($track->completed_at, 'Y-m-d'),
                $this->miniutesToHours($track->billable_minutes),
                $this->sanitizeForCSV($track->message)
            ];
        }

        $writer->insertAll($rows);
        $writer->output('time-sheet-' . date('Y-m-d_H-i') . '.csv');
        die();
    }

    private function verifyRequest()
    {
        $permission = 'fst_view_all_reports';
        if (PermissionManager::currentUserCan($permission)) {
            return true;
        }

        die('You do not have permission');
    }

    private function sanitizeForCSV($content)
    {
        $formulas = ['=', '-', '+', '@', "\t", "\r"];

        if (Str::startsWith($content, $formulas)) {
            $content = "'" . $content;
        }

        return $content;
    }

    /*
     * Convert minutes to hours 30 mins as .5 hours
     */
    private function miniutesToHours($minutes)
    {
        return round($minutes / 60, 2);
    }

    private function formatTime($time, $format = 'Y-m-d H:i:s'): string
    {
        return date($format, strtotime($time));
    }
}
