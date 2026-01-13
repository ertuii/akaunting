<?php

namespace Modules\CostOverview\Models;

use App\Models\Document\Document;

class CostOverview extends Document
{
    public const TYPE = 'cost-overview';

    protected $table = 'documents';

    protected $appends = ['attachment', 'amount_without_tax', 'discount', 'paid', 'received_at', 'status_label', 'sent_at', 'reconciled', 'contact_location', 'budget_utilization', 'budget_status_color'];

    protected $fillable = [
        'company_id',
        'type',
        'document_number',
        'order_number',
        'status',
        'issued_at',
        'due_at',
        'amount',
        'currency_code',
        'currency_rate',
        'discount_type',
        'discount_rate',
        'category_id',
        'contact_id',
        'contact_name',
        'contact_email',
        'contact_tax_number',
        'contact_phone',
        'contact_address',
        'contact_country',
        'contact_state',
        'contact_zip_code',
        'contact_city',
        'title',
        'subheading',
        'notes',
        'footer',
        'template',
        'color',
        'parent_id',
        'created_from',
        'created_by',
        'budget',
    ];

    /**
     * Get the current balance of the document.
     *
     * @return string
     */
    public function getTemplatePathAttribute($value)
    {
        return 'cost-overview::admin.cost-overviews.print';
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        $builder = parent::newEloquentBuilder($query);

        return $builder->where($this->qualifyColumn('type'), '=', self::TYPE);
    }

    /**
     * Get the budget utilization percentage
     *
     * @return float
     */
    public function getBudgetUtilizationAttribute()
    {
        if (empty($this->budget) || $this->budget <= 0) {
            return 0;
        }

        return min(($this->amount / $this->budget) * 100, 100);
    }

    /**
     * Get the budget status color
     *
     * @return string
     */
    public function getBudgetStatusColorAttribute()
    {
        $utilization = $this->budget_utilization;

        if ($utilization < 50) {
            return 'green';
        } elseif ($utilization < 80) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    /**
     * Get the status label for cost overview
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'approved'  => 'status-success',
            'converted' => 'status-success',
            'sent'      => 'status-sent',
            default     => 'status-draft',
        };
    }

    /**
     * Check if the cost overview can be converted to invoice
     *
     * @return bool
     */
    public function canConvertToInvoice()
    {
        return in_array($this->status, ['draft', 'sent', 'approved']) && $this->status !== 'converted';
    }

    /**
     * Scope a query to only include cost overviews.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCostOverview($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', self::TYPE);
    }
}
