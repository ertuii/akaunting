@extends('layouts.admin')

@section('title', trans('cost-overview::general.cost_overview') . ' - ' . $customer->name)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ trans('cost-overview::general.cost_overview') }}</h3>
                            <p class="text-sm mb-0">{{ $customer->name }}</p>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('cost-overviews.print', $customer->id) }}" target="_blank" class="btn btn-sm btn-white">
                                <i class="fas fa-print"></i> {{ trans('cost-overview::general.print') }}
                            </a>
                            <a href="{{ route('cost-overviews.pdf', $customer->id) }}" class="btn btn-sm btn-white">
                                <i class="fas fa-file-pdf"></i> {{ trans('cost-overview::general.download_pdf') }}
                            </a>
                            @if($customer->email)
                                <a href="{{ route('cost-overviews.email', $customer->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-envelope"></i> {{ trans('cost-overview::general.send_email') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="text-sm font-weight-bold">{{ trans('general.customer') }}</h4>
                            <p class="mb-1">{{ $customer->name }}</p>
                            @if($customer->email)
                                <p class="mb-1"><i class="fas fa-envelope"></i> {{ $customer->email }}</p>
                            @endif
                            @if($customer->phone)
                                <p class="mb-1"><i class="fas fa-phone"></i> {{ $customer->phone }}</p>
                            @endif
                            @if($customer->address)
                                <p class="mb-1">{{ $customer->address }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="card bg-gradient-success">
                                <div class="card-body">
                                    <h5 class="text-white mb-0">{{ trans('cost-overview::general.total_outstanding') }}</h5>
                                    <h2 class="text-white mb-0">{!! money($total_outstanding, $customer->currency_code ?? default_currency(), true) !!}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unpaid Invoices -->
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h4 class="font-weight-bold mb-3">{{ trans('cost-overview::general.unpaid_invoices') }}</h4>
                            
                            @if($unpaid_invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-flush">
                                        <thead class="thead-light">
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
                                                    <td>
                                                        <a href="{{ route('invoices.show', $invoice->id) }}" target="_blank">
                                                            {{ $invoice->document_number }}
                                                        </a>
                                                    </td>
                                                    <td>{{ company_date($invoice->issued_at) }}</td>
                                                    <td>{{ company_date($invoice->due_at) }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $invoice->status == 'sent' ? 'warning' : 'info' }}">
                                                            {{ trans('documents.statuses.' . $invoice->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        {!! money($invoice->amount, $invoice->currency_code, true) !!}
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="font-weight-bold text-danger">
                                                            {!! money($invoice->amount_due, $invoice->currency_code, true) !!}
                                                        </span>
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
                                </div>
                            @else
                                <div class="alert alert-info">
                                    {{ trans('cost-overview::general.no_unpaid_invoices') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h4 class="font-weight-bold mb-3">{{ trans('cost-overview::general.recent_transactions') }}</h4>
                            
                            @if($recent_transactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-flush">
                                        <thead class="thead-light">
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
                                </div>
                            @else
                                <div class="alert alert-info">
                                    {{ trans('cost-overview::general.no_transactions') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
