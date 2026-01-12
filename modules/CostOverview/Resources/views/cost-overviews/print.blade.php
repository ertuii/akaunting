<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ trans('cost-overview::general.cost_overview') }} - {{ $customer->name }}</title>
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .print-button {
            margin-bottom: 20px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> {{ trans('general.print') }}
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            {{ trans('general.close') }}
        </button>
    </div>

    <div class="header">
        <div class="row">
            <div class="col-6">
                <h1>{{ trans('cost-overview::general.cost_overview') }}</h1>
            </div>
            <div class="col-6 text-right">
                <strong>{{ company()->name }}</strong><br>
                {{ company()->address }}<br>
                @if(company()->email)
                    {{ trans('general.email') }}: {{ company()->email }}<br>
                @endif
                @if(company()->phone)
                    {{ trans('general.phone') }}: {{ company()->phone }}<br>
                @endif
            </div>
        </div>
    </div>

    <div class="customer-info mb-4">
        <h3>{{ trans('general.customer') }}</h3>
        <p>
            <strong>{{ $customer->name }}</strong><br>
            @if($customer->email)
                {{ trans('general.email') }}: {{ $customer->email }}<br>
            @endif
            @if($customer->phone)
                {{ trans('general.phone') }}: {{ $customer->phone }}<br>
            @endif
            @if($customer->address)
                {{ $customer->address }}<br>
            @endif
        </p>
        <p><strong>{{ trans('general.date') }}:</strong> {{ company_date(now()) }}</p>
    </div>

    <div class="alert alert-info mb-4">
        <h3>{{ trans('cost-overview::general.total_outstanding') }}</h3>
        <h2 class="text-danger">
            {!! money($total_outstanding, $customer->currency_code ?? default_currency(), true) !!}
        </h2>
    </div>

    <div class="section mt-5">
        <h3>{{ trans('cost-overview::general.unpaid_invoices') }}</h3>
        
        @if($unpaid_invoices->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('cost-overview::general.invoice_number') }}</th>
                        <th>{{ trans('cost-overview::general.invoice_date') }}</th>
                        <th>{{ trans('cost-overview::general.due_date') }}</th>
                        <th>{{ trans('cost-overview::general.status') }}</th>
                        <th class="text-right">{{ trans('cost-overview::general.amount') }}</th>
                        <th class="text-right">{{ trans('cost-overview::general.amount_due') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unpaid_invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->document_number }}</td>
                            <td>{{ company_date($invoice->issued_at) }}</td>
                            <td>{{ company_date($invoice->due_at) }}</td>
                            <td>{{ trans('documents.statuses.' . $invoice->status) }}</td>
                            <td class="text-right">
                                {!! money($invoice->amount, $invoice->currency_code, true) !!}
                            </td>
                            <td class="text-right font-weight-bold text-danger">
                                {!! money($invoice->amount_due, $invoice->currency_code, true) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">{{ trans('general.total') }}:</th>
                        <th class="text-right">
                            {!! money($total_outstanding, $customer->currency_code ?? default_currency(), true) !!}
                        </th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>{{ trans('cost-overview::general.no_unpaid_invoices') }}</p>
        @endif
    </div>

    <div class="section mt-5">
        <h3>{{ trans('cost-overview::general.recent_transactions') }}</h3>
        
        @if($recent_transactions->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('cost-overview::general.transaction_date') }}</th>
                        <th>{{ trans('cost-overview::general.description') }}</th>
                        <th>{{ trans('cost-overview::general.category') }}</th>
                        <th class="text-right">{{ trans('cost-overview::general.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent_transactions as $transaction)
                        <tr>
                            <td>{{ company_date($transaction->paid_at) }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->category->name ?? trans('general.na') }}</td>
                            <td class="text-right">
                                {!! money($transaction->amount, $transaction->currency_code, true) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">{{ trans('general.total') }}:</th>
                        <th class="text-right">
                            {!! money($total_paid, $customer->currency_code ?? default_currency(), true) !!}
                        </th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>{{ trans('cost-overview::general.no_transactions') }}</p>
        @endif
    </div>

    <div class="footer mt-5 text-center">
        <p>{{ trans('cost-overview::general.email_outro') }}</p>
        <p>{{ company()->name }} - {{ now()->year }}</p>
    </div>
</body>
</html>
