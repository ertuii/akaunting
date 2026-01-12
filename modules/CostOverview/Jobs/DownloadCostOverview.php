<?php

namespace Modules\CostOverview\Jobs;

use App\Abstracts\Job;
use App\Models\Common\Contact;

class DownloadCostOverview extends Job
{
    protected $customer;

    /**
     * Create a new job instance.
     *
     * @param  Contact  $customer
     * @return void
     */
    public function __construct(Contact $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get unpaid invoices
        $unpaid_invoices = $this->customer->invoices()
            ->whereIn('status', ['sent', 'viewed', 'partial'])
            ->with('items', 'totals', 'currency')
            ->get();

        // Get recent transactions (last 3 months)
        $recent_transactions = $this->customer->transactions()
            ->where('type', 'income')
            ->where('paid_at', '>=', now()->subMonths(3))
            ->with('account', 'currency', 'category')
            ->get();

        // Calculate totals
        $total_outstanding = $unpaid_invoices->sum('amount_due');
        $total_paid = $recent_transactions->sum('amount');

        $data = [
            'customer' => $this->customer,
            'unpaid_invoices' => $unpaid_invoices,
            'recent_transactions' => $recent_transactions,
            'total_outstanding' => $total_outstanding,
            'total_paid' => $total_paid,
            'currency_style' => true
        ];

        $view = view('cost-overview::cost-overviews.pdf', $data)->render();
        
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getFileName();

        return $pdf->download($file_name);
    }

    /**
     * Get the file name for the PDF.
     *
     * @return string
     */
    protected function getFileName(): string
    {
        return 'cost-overview-' . $this->customer->id . '-' . date('Y-m-d') . '.pdf';
    }
}
