<!DOCTYPE html>
<html dir="{{ language()->direction() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <title>{{ $cost_overview->document_number }}</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #000000;
        }

        body {
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
            font-weight: bold;
        }

        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
    </style>
</head>
<body>
    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <h2>{{ setting('company.name') }}</h2>
                <p>
                    {!! setting('company.address') !!}<br>
                    @if(setting('company.tax_number'))
                        {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}<br>
                    @endif
                    {{ setting('company.phone') }}<br>
                    {{ setting('company.email') }}
                </p>
            </td>
            <td width="50%" style="vertical-align: top; text-align: right;">
                <h1>{{ trans('cost-overview::general.singular') }}</h1>
                <p>
                    <strong>{{ trans('cost-overview::general.document_number') }}:</strong> {{ $cost_overview->document_number }}<br>
                    <strong>{{ trans('documents.issued_at') }}:</strong> {{ $cost_overview->issued_at->format(company_date_format()) }}<br>
                    <strong>{{ trans('documents.due_at') }}:</strong> {{ $cost_overview->due_at->format(company_date_format()) }}<br>
                    <strong>{{ trans_choice('general.statuses', 1) }}:</strong> 
                    <span class="badge 
                        @if($cost_overview->status === 'draft') badge-info
                        @elseif($cost_overview->status === 'sent') badge-warning
                        @elseif($cost_overview->status === 'approved') badge-success
                        @else badge-danger
                        @endif">
                        {{ trans('cost-overview::general.status.' . $cost_overview->status) }}
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <h3>{{ trans_choice('general.customers', 1) }}</h3>
                <p>
                    <strong>{{ $cost_overview->contact_name }}</strong><br>
                    @if($cost_overview->contact_email)
                        {{ $cost_overview->contact_email }}<br>
                    @endif
                    @if($cost_overview->contact_phone)
                        {{ $cost_overview->contact_phone }}<br>
                    @endif
                    @if($cost_overview->contact_address)
                        {{ $cost_overview->contact_address }}<br>
                    @endif
                    @if($cost_overview->contact_city || $cost_overview->contact_zip_code)
                        {{ $cost_overview->contact_zip_code }} {{ $cost_overview->contact_city }}<br>
                    @endif
                    @if($cost_overview->contact_country)
                        {{ $cost_overview->contact_country }}<br>
                    @endif
                </p>
            </td>
            @if($cost_overview->budget)
            <td width="50%" style="vertical-align: top; text-align: right;">
                <h3>{{ trans('cost-overview::general.budget_status') }}</h3>
                <p>
                    <strong>{{ trans('cost-overview::general.budget') }}:</strong> @money($cost_overview->budget, $cost_overview->currency_code, true)<br>
                    <strong>{{ trans('general.amount') }}:</strong> @money($cost_overview->amount, $cost_overview->currency_code, true)<br>
                    <strong>{{ trans('cost-overview::general.budget_utilization') }}:</strong> {{ number_format($cost_overview->budget_utilization, 1) }}%<br>
                    <span class="badge 
                        @if($cost_overview->budget_status_color === 'green') badge-success
                        @elseif($cost_overview->budget_status_color === 'yellow') badge-warning
                        @else badge-danger
                        @endif">
                        {{ trans('cost-overview::general.budget_colors.' . $cost_overview->budget_status_color) }}
                    </span>
                </p>
            </td>
            @endif
        </tr>
    </table>

    <table width="100%" border="1" style="margin-bottom: 30px; border: 1px solid #ddd;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 10px; text-align: left;">{{ trans_choice('general.items', 1) }}</th>
                <th style="padding: 10px; text-align: center;">{{ trans('general.quantity') }}</th>
                <th style="padding: 10px; text-align: right;">{{ trans('general.price') }}</th>
                <th style="padding: 10px; text-align: right;">{{ trans('general.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cost_overview->items as $item)
            <tr>
                <td style="padding: 10px;">
                    <strong>{{ $item->name }}</strong>
                    @if($item->description)
                        <br><small>{{ $item->description }}</small>
                    @endif
                </td>
                <td style="padding: 10px; text-align: center;">{{ $item->quantity }}</td>
                <td style="padding: 10px; text-align: right;">@money($item->price, $cost_overview->currency_code, true)</td>
                <td style="padding: 10px; text-align: right;">@money($item->total, $cost_overview->currency_code, true)</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            @foreach($cost_overview->totals as $total)
            <tr>
                <td colspan="3" style="padding: 10px; text-align: right;"><strong>{{ $total->name }}</strong></td>
                <td style="padding: 10px; text-align: right;"><strong>@money($total->amount, $cost_overview->currency_code, true)</strong></td>
            </tr>
            @endforeach
        </tfoot>
    </table>

    @if($cost_overview->notes)
    <div style="margin-bottom: 20px;">
        <h4>{{ trans_choice('general.notes', 2) }}</h4>
        <p>{{ $cost_overview->notes }}</p>
    </div>
    @endif

    @if($cost_overview->footer)
    <div style="margin-bottom: 20px;">
        <h4>{{ trans('general.footer') }}</h4>
        <p>{{ $cost_overview->footer }}</p>
    </div>
    @endif
</body>
</html>
