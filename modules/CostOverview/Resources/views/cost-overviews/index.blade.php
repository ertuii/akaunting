@extends('layouts.admin')

@section('title', trans('cost-overview::general.cost_overviews'))

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="mb-0">{{ trans('cost-overview::general.cost_overviews') }}</h3>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-md-4 text-left">{{ trans('cost-overview::general.customer') }}</th>
                        <th class="col-md-3 text-left">{{ trans('general.email') }}</th>
                        <th class="col-md-2 text-right">{{ trans('cost-overview::general.total_outstanding') }}</th>
                        <th class="col-md-3 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($customers as $customer)
                        @php
                            $unpaid_total = $customer->invoices()
                                ->whereIn('status', ['sent', 'viewed', 'partial'])
                                ->sum('amount_due');
                        @endphp
                        <tr class="row align-items-center border-top-1">
                            <td class="col-md-4 text-left">
                                <a href="{{ route('cost-overviews.show', $customer->id) }}" class="font-weight-bold">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td class="col-md-3 text-left">
                                {{ $customer->email ?? trans('general.na') }}
                            </td>
                            <td class="col-md-2 text-right">
                                <span class="font-weight-bold {{ $unpaid_total > 0 ? 'text-danger' : 'text-success' }}">
                                    {!! money($unpaid_total, $customer->currency_code ?? default_currency(), true) !!}
                                </span>
                            </td>
                            <td class="col-md-3 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only btn-outline-secondary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('cost-overviews.show', $customer->id) }}">
                                            <i class="fas fa-eye"></i> {{ trans('general.show') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('cost-overviews.pdf', $customer->id) }}">
                                            <i class="fas fa-file-pdf"></i> {{ trans('cost-overview::general.download_pdf') }}
                                        </a>
                                        @if($customer->email)
                                            <a class="dropdown-item" href="{{ route('cost-overviews.email', $customer->id) }}">
                                                <i class="fas fa-envelope"></i> {{ trans('cost-overview::general.send_email') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($customers->count() == 0)
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <span class="text-muted">{{ trans('general.no_records') }}</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
