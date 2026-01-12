<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ trans('cost-overview::general.cost_overview') }} - {{ $customer->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-info {
            float: right;
            text-align: right;
        }
        .customer-info {
            margin-top: 20px;
        }
        .totals-box {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-success {
            color: #28a745;
        }
        h1, h2, h3, h4 {
            margin: 10px 0;
        }
        .section {
            margin-top: 30px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ trans('cost-overview::general.cost_overview') }}</h1>
        <div class="company-info">
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

    <div class="customer-info">
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

    <div class="totals-box">
        <h3>{{ trans('cost-overview::general.total_outstanding') }}</h3>
        <h2 class="text-danger">
            {!! money($total_outstanding, $customer->currency_code ?? default_currency(), true) !!}
        </h2>
    </div>

    <div class="section">
        <h3>{{ trans('cost-overview::general.unpaid_invoices') }}</h3>
        
        @if($unpaid_invoices->count() > 0)
            <table>
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

    <div class="section">
        <h3>{{ trans('cost-overview::general.recent_transactions') }}</h3>
        
        @if($recent_transactions->count() > 0)
            <table>
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

    <div class="footer">
        <p>{{ trans('cost-overview::general.email_outro') }}</p>
        <p>{{ company()->name }} - {{ now()->year }}</p>
    </div>
</body>
</html>
