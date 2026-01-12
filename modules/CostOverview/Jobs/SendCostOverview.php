<?php

namespace Modules\CostOverview\Jobs;

use App\Abstracts\Job;
use App\Models\Common\Contact;
use Modules\CostOverview\Notifications\CostOverview as CostOverviewNotification;

class SendCostOverview extends Job
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
     * @return void
     */
    public function handle(): void
    {
        if (empty($this->customer->email)) {
            return;
        }

        // Notify the customer
        $this->customer->notify(new CostOverviewNotification($this->customer));
    }
}
