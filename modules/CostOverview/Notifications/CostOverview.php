<?php

namespace Modules\CostOverview\Notifications;

use App\Abstracts\Notification;
use App\Models\Common\Contact;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Mail\Attachment;

class CostOverview extends Notification
{
    protected $customer;

    /**
     * Create a notification instance.
     *
     * @param  Contact  $customer
     * @return void
     */
    public function __construct(Contact $customer)
    {
        parent::__construct();

        $this->customer = $customer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
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

        $file_name = 'cost-overview-' . $this->customer->id . '-' . date('Y-m-d') . '.pdf';
        
        // Save the PDF to temp location
        $temp_path = storage_path('app/temp/' . $file_name);
        
        if (!file_exists(dirname($temp_path))) {
            mkdir(dirname($temp_path), 0755, true);
        }
        
        $pdf->save($temp_path);

        $subject = trans('cost-overview::general.email_subject', [
            'company' => company()->name,
        ]);

        $greeting = trans('cost-overview::general.email_greeting', [
            'customer' => $this->customer->name,
        ]);

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line(trans('cost-overview::general.email_intro'))
            ->line(trans('cost-overview::general.email_body', [
                'total_outstanding' => money($total_outstanding, $this->customer->currency_code ?? default_currency(), true),
            ]));

        if (file_exists($temp_path)) {
            $file = Attachment::fromPath($temp_path)->withMime('application/pdf');
            $message->attach($file);
        }

        $message->line(trans('cost-overview::general.email_outro'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
        ];
    }
}
