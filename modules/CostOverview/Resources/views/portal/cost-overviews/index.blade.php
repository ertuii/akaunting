<x-layouts.portal>
    <x-slot name="title">
        {{ trans('cost-overview::general.name') }}
    </x-slot>

    <x-slot name="content">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">{{ trans('cost-overview::general.name') }}</h3>
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                                {{ trans_choice('general.numbers', 1) }}
                            </th>

                            <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">
                                {{ trans_choice('general.statuses', 1) }}
                            </th>

                            <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block">
                                {{ trans('documents.issued_at') }}
                            </th>

                            <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">
                                {{ trans('general.amount') }}
                            </th>

                            <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">
                                {{ trans('cost-overview::general.budget') }}
                            </th>

                            <th class="col-xs-4 col-sm-5 col-md-3 col-lg-4 col-xl-4 text-center">
                                {{ trans('general.actions') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cost_overviews as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                                    <x-link href="{{ route('portal.cost-overviews.show', $item->id) }}" override="class">
                                        {{ $item->document_number }}
                                    </x-link>
                                </td>

                                <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">
                                    <x-show.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" />
                                </td>

                                <td class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block">
                                    {{ $item->issued_at->format(company_date_format()) }}
                                </td>

                                <td class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">
                                    <x-money :amount="$item->amount" :currency="$item->currency_code" />
                                </td>

                                <td class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">
                                    @if($item->budget)
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $item->budget_status_color }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $item->budget_utilization }}%"
                                                 aria-valuenow="{{ $item->budget_utilization }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ number_format($item->budget_utilization, 0) }}%
                                            </div>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="col-xs-4 col-sm-5 col-md-3 col-lg-4 col-xl-4 text-center">
                                    <x-link href="{{ route('portal.cost-overviews.show', $item->id) }}" kind="primary" size="sm">
                                        {{ trans('general.show') }}
                                    </x-link>

                                    <x-link href="{{ route('portal.cost-overviews.pdf', $item->id) }}" kind="secondary" size="sm">
                                        {{ trans('general.download') }}
                                    </x-link>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer table-action">
                <div class="row">
                    @include('partials.portal.pagination', ['items' => $cost_overviews])
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.portal>
