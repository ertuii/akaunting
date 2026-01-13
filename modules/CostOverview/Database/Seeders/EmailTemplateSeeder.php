<?php

namespace Modules\CostOverview\Database\Seeders;

use App\Abstracts\Model;
use App\Jobs\Setting\CreateEmailTemplate;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    use Jobs;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = company_id();

        $templates = [
            [
                'alias' => 'cost_overview_new_customer',
                'class' => 'Modules\CostOverview\Notifications\CostOverviewNotification',
                'name' => 'cost-overview::email.templates.cost_overview_new_customer',
                'subject' => 'New Cost Overview: {cost_overview_number}',
                'body' => 'Dear {customer_name}, we have prepared a new cost overview for you. Cost Overview Number: {cost_overview_number}, Amount: {amount}, Budget: {budget}.',
            ],
        ];

        foreach ($templates as $template) {
            // Check if template already exists
            $exists = \App\Models\Setting\EmailTemplate::where('company_id', $company_id)
                ->where('alias', $template['alias'])
                ->first();

            if (!$exists) {
                $this->dispatch(new CreateEmailTemplate([
                    'company_id' => $company_id,
                    'alias' => $template['alias'],
                    'class' => $template['class'],
                    'name' => $template['name'],
                    'subject' => $template['subject'],
                    'body' => $template['body'],
                ]));
            }
        }
    }
}
