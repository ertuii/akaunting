<x-layouts.admin>
    <x-slot name="title">
        {{ trans('cost-overview::general.singular') . ': ' . $cost_overview->document_number }}
    </x-slot>

    <x-slot name="status">
        <x-show.status status="{{ $cost_overview->status }}" background-color="bg-{{ $cost_overview->status_label }}" text-color="text-text-{{ $cost_overview->status_label }}" />
    </x-slot>

    <x-slot name="buttons">
        @if($cost_overview->status === 'draft')
            <x-link href="{{ route('cost-overviews.email', $cost_overview->id) }}" kind="secondary">
                {{ trans('cost-overview::general.actions.send') }}
            </x-link>
        @endif

        @if(in_array($cost_overview->status, ['draft', 'sent']))
            <x-link href="{{ route('cost-overviews.approved', $cost_overview->id) }}" kind="success">
                {{ trans('cost-overview::general.actions.mark_approved') }}
            </x-link>
        @endif

        @if($cost_overview->canConvertToInvoice())
            <x-form.button.link href="{{ route('cost-overviews.convert-to-invoice', $cost_overview->id) }}" kind="primary" override="class" form-id="cost-overview-convert">
                {{ trans('cost-overview::general.actions.convert_to_invoice') }}
            </x-form.button.link>

            <form id="cost-overview-convert" method="POST" action="{{ route('cost-overviews.convert-to-invoice', $cost_overview->id) }}">
                @csrf
            </form>
        @endif

        <x-dropdown id="dropdown-more-actions">
            <x-slot name="button">
                <i class="fa fa-ellipsis-h"></i>
            </x-slot>

            <x-dropdown.link href="{{ route('cost-overviews.print', $cost_overview->id) }}" target="_blank">
                {{ trans('cost-overview::general.actions.print') }}
            </x-dropdown.link>

            <x-dropdown.link href="{{ route('cost-overviews.pdf', $cost_overview->id) }}">
                {{ trans('cost-overview::general.actions.download') }}
            </x-dropdown.link>

            <x-dropdown.link href="{{ route('cost-overviews.duplicate', $cost_overview->id) }}">
                {{ trans('cost-overview::general.actions.duplicate') }}
            </x-dropdown.link>

            <x-dropdown.divider />

            <x-dropdown.link href="{{ route('cost-overviews.edit', $cost_overview->id) }}">
                {{ trans('general.edit') }}
            </x-dropdown.link>

            <x-delete-link :model="$cost_overview" route="cost-overviews.destroy" />
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('general.document_number') }}:</th>
                                        <td>{{ $cost_overview->document_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans_choice('general.customers', 1) }}:</th>
                                        <td>{{ $cost_overview->contact_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('documents.issued_at') }}:</th>
                                        <td>{{ $cost_overview->issued_at->format(company_date_format()) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('documents.due_at') }}:</th>
                                        <td>{{ $cost_overview->due_at->format(company_date_format()) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans_choice('general.statuses', 1) }}:</th>
                                        <td>
                                            <x-show.status status="{{ $cost_overview->status }}" background-color="bg-{{ $cost_overview->status_label }}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @if($cost_overview->budget)
                        <div class="card bg-light">
                            <div class="card-header">
                                <h4 class="card-title">{{ trans('cost-overview::general.budget_status') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ trans('cost-overview::general.budget') }}:</span>
                                        <strong><x-money :amount="$cost_overview->budget" :currency="$cost_overview->currency_code" /></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ trans('general.amount') }}:</span>
                                        <strong><x-money :amount="$cost_overview->amount" :currency="$cost_overview->currency_code" /></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>{{ trans('cost-overview::general.budget_utilization') }}:</span>
                                        <strong>{{ number_format($cost_overview->budget_utilization, 1) }}%</strong>
                                    </div>
                                </div>

                                <div class="progress" style="height: 30px;">
                                    <div class="progress-bar 
                                        @if($cost_overview->budget_status_color === 'green') bg-success
                                        @elseif($cost_overview->budget_status_color === 'yellow') bg-warning
                                        @else bg-danger
                                        @endif" 
                                         role="progressbar" 
                                         style="width: {{ $cost_overview->budget_utilization }}%"
                                         aria-valuenow="{{ $cost_overview->budget_utilization }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <strong>{{ number_format($cost_overview->budget_utilization, 0) }}%</strong>
                                    </div>
                                </div>

                                <div class="mt-3 text-center">
                                    @if($cost_overview->budget_status_color === 'green')
                                        <span class="badge badge-success">{{ trans('cost-overview::general.budget_colors.green') }}</span>
                                    @elseif($cost_overview->budget_status_color === 'yellow')
                                        <span class="badge badge-warning">{{ trans('cost-overview::general.budget_colors.yellow') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ trans('cost-overview::general.budget_colors.red') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-left">{{ trans_choice('general.items', 1) }}</th>
                                        <th class="text-center">{{ trans('general.quantity') }}</th>
                                        <th class="text-right">{{ trans('general.price') }}</th>
                                        <th class="text-right">{{ trans('general.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cost_overview->items as $item)
                                    <tr>
                                        <td class="text-left">
                                            <strong>{{ $item->name }}</strong>
                                            @if($item->description)
                                                <br><small class="text-muted">{{ $item->description }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right"><x-money :amount="$item->price" :currency="$cost_overview->currency_code" /></td>
                                        <td class="text-right"><x-money :amount="$item->total" :currency="$cost_overview->currency_code" /></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @foreach($cost_overview->totals as $total)
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>{{ $total->name }}</strong></td>
                                        <td class="text-right"><strong><x-money :amount="$total->amount" :currency="$cost_overview->currency_code" /></strong></td>
                                    </tr>
                                    @endforeach
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if($cost_overview->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>{{ trans_choice('general.notes', 2) }}</h5>
                        <p>{{ $cost_overview->notes }}</p>
                    </div>
                </div>
                @endif

                @if($cost_overview->footer)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>{{ trans('general.footer') }}</h5>
                        <p>{{ $cost_overview->footer }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- History Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h4 class="card-title">{{ trans_choice('general.histories', 2) }}</h4>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($cost_overview->histories as $history)
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <span class="badge badge-info">{{ trans('cost-overview::general.status.' . $history->status) }}</span>
                                <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                            </div>
                            @if($history->description)
                                <p class="mt-2 mb-0">{{ $history->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-slot>

    <x-script alias="cost-overview" file="cost-overviews" />
</x-layouts.admin>
