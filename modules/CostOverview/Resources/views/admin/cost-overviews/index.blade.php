<x-layouts.admin>
    <x-slot name="title">
        {{ trans('cost-overview::general.name') }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans('cost-overview::general.name') }}"
        icon="file-text"
        route="cost-overviews.index"
    ></x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('cost-overviews.create') }}" kind="primary">
            {{ trans('general.title.new', ['type' => trans('cost-overview::general.singular')]) }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        <div class="card">
            <div class="card-header border-bottom-0" :class="[bulk_action.show ? 'bg-gradient-primary' : '']">
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="App\Models\Document\Document" />
                </div>

                <x-bulk-action.top-bar bulk-action-class="App\BulkActions\Sales\CostOverviews" />
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">
                                <x-bulk-action.check-all />
                            </th>

                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                                <x-sortable-column field="document_number" text="{{ trans_choice('general.numbers', 1) }}" />
                            </th>

                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                <x-sortable-column field="contact_name" text="{{ trans_choice('general.customers', 1) }}" />
                            </th>

                            <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">
                                <x-sortable-column field="status" text="{{ trans_choice('general.statuses', 1) }}" />
                            </th>

                            <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">
                                <x-sortable-column field="amount" text="{{ trans('general.amount') }}" />
                            </th>

                            <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">
                                {{ trans('cost-overview::general.budget') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cost_overviews as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">
                                    <x-bulk-action.check id="{{ $item->id }}" name="{{ $item->document_number }}" />
                                </td>

                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                                    <x-link href="{{ route('cost-overviews.show', $item->id) }}" override="class">
                                        {{ $item->document_number }}
                                    </x-link>
                                </td>

                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                    {{ $item->contact_name }}
                                </td>

                                <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">
                                    <x-show.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" />
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

                                <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-right">
                                    <x-dropdown id="dropdown-actions-{{ $item->id }}">
                                        <x-slot name="button">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </x-slot>

                                        <x-dropdown.link href="{{ route('cost-overviews.edit', $item->id) }}">
                                            {{ trans('general.edit') }}
                                        </x-dropdown.link>

                                        <x-dropdown.link href="{{ route('cost-overviews.duplicate', $item->id) }}">
                                            {{ trans('general.duplicate') }}
                                        </x-dropdown.link>

                                        <x-delete-link :model="$item" route="cost-overviews.destroy" />
                                    </x-dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer table-action">
                <div class="row">
                    @include('partials.admin.pagination', ['items' => $cost_overviews, 'type' => 'cost_overviews'])
                </div>
            </div>
        </div>
    </x-slot>

    <x-script alias="cost-overview" file="cost-overviews" />
</x-layouts.admin>
