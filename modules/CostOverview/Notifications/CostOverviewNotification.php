<?php

namespace Modules\CostOverview\Notifications;

use App\Abstracts\Notification;
use App\Models\Setting\EmailTemplate;
use Modules\CostOverview\Models\CostOverview;
use App\Traits\Documents;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;

class CostOverviewNotification extends Notification
{
    use Documents;

    /**
     * The cost overview model.
     *
     * @var object
     */
    public $cost_overview;

    /**
     * The email template.
     *
     * @var EmailTemplate
     */
    public $template;

    /**
     * Should attach pdf or not.
     *
     * @var bool
     */
    public $attach_pdf;

    /**
     * List of document attachments to attach when sending the email.
     *
     * @var array
     */
    public $attachments;

    /**
     * Create a notification instance.
     */
    public function __construct(CostOverview $cost_overview = null, string $template_alias = null, bool $attach_pdf = false, array $custom_mail = [], $attachments = [])
    {
        parent::__construct();

        $this->cost_overview = $cost_overview;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
        $this->custom_mail = $custom_mail;
        $this->attachments = $attachments;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        if (!empty($this->custom_mail['to'])) {
            $notifiable->email = $this->custom_mail['to'];
        }

        $message = $this->initMailMessage();

        $func = is_local_storage() ? 'fromPath' : 'fromStorage';

        // Attach the PDF file
        if ($this->attach_pdf) {
            $path = $this->storeDocumentPdfAndGetPath($this->cost_overview);
            $file = Attachment::$func($path)->withMime('application/pdf');

            $message->attach($file);
        }

        // Attach selected attachments
        if (! empty($this->cost_overview->attachment)) {
            foreach ($this->cost_overview->attachment as $attachment) {
                if (! in_array($attachment->id, $this->attachments)) {
                    continue;
                }

                $path = is_local_storage() ? $attachment->getAbsolutePath() : $attachment->getDiskPath();
                $file = Attachment::$func($path)->withMime($attachment->mime_type);

                $message->attach($file);
            }
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'cost_overview_id' => $this->cost_overview->id,
            'amount' => $this->cost_overview->amount,
        ];
    }

    public function initMailMessage()
    {
        $message = (new MailMessage)
            ->line(trans($this->template->body, $this->getTagsForTemplate()))
            ->action(trans('cost-overview::general.actions.view'), $this->getCostOverviewUrl())
            ->subject(trans($this->template->subject, $this->getTagsForTemplate()));

        return $message;
    }

    public function getCostOverviewUrl()
    {
        return route('portal.cost-overviews.show', [$this->cost_overview->company_id, $this->cost_overview->id]);
    }

    public function getTagsForTemplate()
    {
        return [
            'cost_overview_number' => $this->cost_overview->document_number,
            'customer_name' => $this->cost_overview->contact_name,
            'amount' => money($this->cost_overview->amount, $this->cost_overview->currency_code)->format(),
            'budget' => $this->cost_overview->budget ? money($this->cost_overview->budget, $this->cost_overview->currency_code)->format() : 'N/A',
        ];
    }
}
