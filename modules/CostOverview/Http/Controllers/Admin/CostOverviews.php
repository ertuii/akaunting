<?php

namespace Modules\CostOverview\Http\Controllers\Admin;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Document\Document as Request;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Jobs\Document\SendDocument;
use App\Models\Document\Document;
use Modules\CostOverview\Models\CostOverview;
use App\Traits\Documents;

class CostOverviews extends Controller
{
    use Documents;

    public string $type = CostOverview::TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cost_overviews = CostOverview::with('contact', 'items', 'items.taxes', 'item_taxes', 'last_history', 'totals', 'histories', 'media')
            ->collect(['document_number' => 'desc']);

        $total_cost_overviews = CostOverview::count();

        return $this->response('cost-overview::admin.cost-overviews.index', compact('cost_overviews', 'total_cost_overviews'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function show(CostOverview $cost_overview)
    {
        $cost_overview->load([
            'items.taxes.tax',
            'items.item',
            'totals',
            'contact',
            'currency',
            'category',
            'histories',
            'media',
        ]);

        return view('cost-overview::admin.cost-overviews.show', compact('cost_overview'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('cost-overview::admin.cost-overviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateDocument($request));

        if ($response['success']) {
            $parameters = ['cost_overview' => $response['data']->id];

            if ($request->has('senddocument')) {
                $parameters['senddocument'] = true;
            }

            $response['redirect'] = route('cost-overviews.show', $parameters);

            $message = trans('cost-overview::general.messages.created');

            flash($message)->success();
        } else {
            $response['redirect'] = route('cost-overviews.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function edit(CostOverview $cost_overview)
    {
        return view('cost-overview::admin.cost-overviews.edit', compact('cost_overview'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CostOverview $cost_overview
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(CostOverview $cost_overview, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($cost_overview, $request));

        if ($response['success']) {
            $parameters = ['cost_overview' => $response['data']->id];

            if ($request->has('senddocument')) {
                $parameters['senddocument'] = true;
            }

            $response['redirect'] = route('cost-overviews.show', $parameters);

            $message = trans('cost-overview::general.messages.updated');

            flash($message)->success();
        } else {
            $response['redirect'] = route('cost-overviews.edit', $cost_overview->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function destroy(CostOverview $cost_overview)
    {
        $response = $this->ajaxDispatch(new DeleteDocument($cost_overview));

        $response['redirect'] = route('cost-overviews.index');

        if ($response['success']) {
            $message = trans('cost-overview::general.messages.deleted');

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function duplicate(CostOverview $cost_overview)
    {
        $clone = $this->dispatch(new DuplicateDocument($cost_overview));

        $message = trans('cost-overview::general.messages.duplicated');

        flash($message)->success();

        return redirect()->route('cost-overviews.edit', $clone->id);
    }

    /**
     * Send the cost overview via email.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function email(CostOverview $cost_overview)
    {
        if (empty($cost_overview->contact_email)) {
            return redirect()->back()->withInput()->withErrors(['message' => trans('cost-overview::general.errors.email_required')]);
        }

        $this->dispatch(new SendDocument($cost_overview));

        $message = trans('cost-overview::general.messages.sent');

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Print the cost overview.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function print(CostOverview $cost_overview)
    {
        event(new \App\Events\Document\DocumentPrinting($cost_overview));

        $view = view($cost_overview->template_path, compact('cost_overview'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of cost overview.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function pdf(CostOverview $cost_overview)
    {
        $currency_style = true;

        event(new \App\Events\Document\DocumentPrinting($cost_overview));

        $view = view($cost_overview->template_path, compact('cost_overview', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getDocumentFileName($cost_overview);

        return $pdf->download($file_name);
    }

    /**
     * Convert cost overview to invoice.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function convertToInvoice(CostOverview $cost_overview)
    {
        if (!$cost_overview->canConvertToInvoice()) {
            $message = trans('cost-overview::general.errors.cannot_convert');

            flash($message)->error();

            return redirect()->back();
        }

        // Create a new invoice from the cost overview
        $invoice_data = [
            'company_id' => $cost_overview->company_id,
            'type' => Document::INVOICE_TYPE,
            'document_number' => $this->getNextDocumentNumber(Document::INVOICE_TYPE),
            'order_number' => $cost_overview->order_number,
            'status' => 'draft',
            'issued_at' => now(),
            'due_at' => now()->addDays(30),
            'amount' => $cost_overview->amount,
            'currency_code' => $cost_overview->currency_code,
            'currency_rate' => $cost_overview->currency_rate,
            'category_id' => $cost_overview->category_id,
            'contact_id' => $cost_overview->contact_id,
            'contact_name' => $cost_overview->contact_name,
            'contact_email' => $cost_overview->contact_email,
            'contact_tax_number' => $cost_overview->contact_tax_number,
            'contact_phone' => $cost_overview->contact_phone,
            'contact_address' => $cost_overview->contact_address,
            'contact_city' => $cost_overview->contact_city,
            'contact_zip_code' => $cost_overview->contact_zip_code,
            'contact_state' => $cost_overview->contact_state,
            'contact_country' => $cost_overview->contact_country,
            'notes' => $cost_overview->notes,
            'footer' => $cost_overview->footer,
            'parent_id' => $cost_overview->id,
        ];

        // Copy items
        $items = [];
        foreach ($cost_overview->items as $item) {
            $items[] = [
                'item_id' => $item->item_id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax' => $item->tax,
                'discount_type' => $item->discount_type,
                'discount_rate' => $item->discount_rate,
            ];
        }

        $invoice_data['items'] = $items;

        $invoice = $this->dispatch(new CreateDocument((object) $invoice_data));

        // Update cost overview status to converted
        $cost_overview->status = 'converted';
        $cost_overview->save();

        $message = trans('cost-overview::general.messages.converted');

        flash($message)->success();

        return redirect()->route('invoices.show', $invoice->id);
    }

    /**
     * Mark the cost overview as sent.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function markSent(CostOverview $cost_overview)
    {
        event(new \App\Events\Document\DocumentMarkedSent($cost_overview));

        $message = trans('cost-overview::general.messages.marked_sent');

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Mark the cost overview as approved.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function markApproved(CostOverview $cost_overview)
    {
        $cost_overview->status = 'approved';
        $cost_overview->save();

        // Create history
        $cost_overview->histories()->create([
            'company_id' => $cost_overview->company_id,
            'type' => $cost_overview->type,
            'document_id' => $cost_overview->id,
            'status' => 'approved',
            'notify' => 0,
            'description' => trans('cost-overview::general.status.approved'),
        ]);

        $message = trans('cost-overview::general.messages.marked_approved');

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Get document file name.
     */
    protected function getDocumentFileName($document)
    {
        return \Illuminate\Support\Str::slug($document->document_number, '-', language()->getShortCode()) . '.pdf';
    }

    /**
     * Get next document number.
     */
    protected function getNextDocumentNumber($type)
    {
        $prefix = $type === Document::INVOICE_TYPE ? 'INV-' : 'CO-';
        $last = Document::where('type', $type)
            ->where('company_id', company_id())
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $number = (int) str_replace($prefix, '', $last->document_number);
            return $prefix . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
        }

        return $prefix . '00001';
    }
}
