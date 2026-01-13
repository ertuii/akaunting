<?php

namespace Modules\CostOverview\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Portal\InvoiceShow as Request;
use Modules\CostOverview\Models\CostOverview;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Documents;

class CostOverviews extends Controller
{
    use DateTime, Currencies, Documents;

    public $type = CostOverview::TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cost_overviews = CostOverview::with('contact', 'histories', 'items')
            ->where('contact_id', user()->contact->id)
            ->collect(['document_number' => 'desc']);

        return $this->response('cost-overview::portal.cost-overviews.index', compact('cost_overviews'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function show(CostOverview $cost_overview, Request $request)
    {
        // Ensure user can only view their own cost overviews
        if ($cost_overview->contact_id !== user()->contact->id) {
            abort(403);
        }

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

        event(new \App\Events\Document\DocumentViewed($cost_overview));

        return view('cost-overview::portal.cost-overviews.show', compact('cost_overview'));
    }

    /**
     * Print the cost overview.
     *
     * @param  CostOverview $cost_overview
     *
     * @return Response
     */
    public function print(CostOverview $cost_overview, Request $request)
    {
        // Ensure user can only print their own cost overviews
        if ($cost_overview->contact_id !== user()->contact->id) {
            abort(403);
        }

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
    public function pdf(CostOverview $cost_overview, Request $request)
    {
        // Ensure user can only download their own cost overviews
        if ($cost_overview->contact_id !== user()->contact->id) {
            abort(403);
        }

        $currency_style = true;

        event(new \App\Events\Document\DocumentPrinting($cost_overview));

        $view = view($cost_overview->template_path, compact('cost_overview', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = \Illuminate\Support\Str::slug($cost_overview->document_number, '-', language()->getShortCode()) . '.pdf';

        return $pdf->download($file_name);
    }
}
