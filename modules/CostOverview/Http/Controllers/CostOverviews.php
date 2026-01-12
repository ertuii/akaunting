<?php

namespace Modules\CostOverview\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Banking\Transaction;
use Illuminate\Http\Request;
use Modules\CostOverview\Jobs\SendCostOverview;
use Modules\CostOverview\Jobs\DownloadCostOverview;

class CostOverviews extends Controller
{
    /**
     * Display a listing of cost overviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Contact::where('type', Contact::CUSTOMER_TYPE)
            ->with(['invoices' => function($query) {
                $query->whereIn('status', ['sent', 'viewed', 'partial']);
            }])
            ->enabled()
            ->collect(['name' => 'asc']);

        // Calculate unpaid totals for each customer
        foreach ($customers as $customer) {
            $customer->unpaid_total = $customer->invoices->sum('amount_due');
        }

        return view('cost-overview::cost-overviews.index', compact('customers'));
    }

    /**
     * Show the cost overview for a specific customer.
     *
     * @param  Contact  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($customer_id)
    {
        $customer = Contact::where('type', Contact::CUSTOMER_TYPE)
            ->with(['invoices' => function($query) {
                $query->orderBy('issued_at', 'desc');
            }, 'transactions' => function($query) {
                $query->where('type', 'income')->orderBy('paid_at', 'desc');
            }])
            ->findOrFail($customer_id);

        // Get unpaid invoices
        $unpaid_invoices = $customer->invoices()
            ->whereIn('status', ['sent', 'viewed', 'partial'])
            ->with('items', 'totals', 'currency')
            ->get();

        // Get recent transactions (last 3 months)
        $recent_transactions = $customer->transactions()
            ->where('type', 'income')
            ->where('paid_at', '>=', now()->subMonths(3))
            ->with('account', 'currency', 'category')
            ->get();

        // Calculate totals
        $total_outstanding = $unpaid_invoices->sum('amount_due');
        $total_paid = $recent_transactions->sum('amount');

        return view('cost-overview::cost-overviews.show', compact(
            'customer',
            'unpaid_invoices',
            'recent_transactions',
            'total_outstanding',
            'total_paid'
        ));
    }

    /**
     * Send cost overview via email.
     *
     * @param  Request  $request
     * @param  int  $customer_id
     * @return \Illuminate\Http\Response
     */
    public function email(Request $request, $customer_id)
    {
        $customer = Contact::where('type', Contact::CUSTOMER_TYPE)
            ->findOrFail($customer_id);

        if (empty($customer->email)) {
            return response()->json([
                'success' => false,
                'error' => trans('cost-overview::general.email_missing'),
            ]);
        }

        dispatch(new SendCostOverview($customer));

        $message = trans('cost-overview::messages.email_sent', ['customer' => $customer->name]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'message' => $message,
            'redirect' => route('cost-overviews.show', $customer_id),
        ]);
    }

    /**
     * Download cost overview as PDF.
     *
     * @param  int  $customer_id
     * @return \Illuminate\Http\Response
     */
    public function pdf($customer_id)
    {
        $customer = Contact::where('type', Contact::CUSTOMER_TYPE)
            ->with(['invoices' => function($query) {
                $query->whereIn('status', ['sent', 'viewed', 'partial'])
                      ->with('items', 'totals', 'currency');
            }, 'transactions' => function($query) {
                $query->where('type', 'income')
                      ->where('paid_at', '>=', now()->subMonths(3))
                      ->with('account', 'currency', 'category');
            }])
            ->findOrFail($customer_id);

        return $this->ajaxDispatch(new DownloadCostOverview($customer));
    }

    /**
     * Show the print view for cost overview.
     *
     * @param  int  $customer_id
     * @return \Illuminate\Http\Response
     */
    public function print($customer_id)
    {
        $customer = Contact::where('type', Contact::CUSTOMER_TYPE)
            ->with(['invoices' => function($query) {
                $query->whereIn('status', ['sent', 'viewed', 'partial'])
                      ->with('items', 'totals', 'currency');
            }, 'transactions' => function($query) {
                $query->where('type', 'income')
                      ->where('paid_at', '>=', now()->subMonths(3))
                      ->with('account', 'currency', 'category');
            }])
            ->findOrFail($customer_id);

        // Get unpaid invoices
        $unpaid_invoices = $customer->invoices()
            ->whereIn('status', ['sent', 'viewed', 'partial'])
            ->with('items', 'totals', 'currency')
            ->get();

        // Get recent transactions (last 3 months)
        $recent_transactions = $customer->transactions()
            ->where('type', 'income')
            ->where('paid_at', '>=', now()->subMonths(3))
            ->with('account', 'currency', 'category')
            ->get();

        // Calculate totals
        $total_outstanding = $unpaid_invoices->sum('amount_due');
        $total_paid = $recent_transactions->sum('amount');

        return view('cost-overview::cost-overviews.print', compact(
            'customer',
            'unpaid_invoices',
            'recent_transactions',
            'total_outstanding',
            'total_paid'
        ));
    }
}
