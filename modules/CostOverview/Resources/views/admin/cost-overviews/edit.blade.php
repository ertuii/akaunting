<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans('cost-overview::general.singular')]) }}
    </x-slot>

    <x-slot name="content">
        <x-form id="cost-overview" method="PATCH" :route="['cost-overviews.update', $cost_overview->id]" enctype="multipart/form-data">
            <x-form.section>
                <x-slot name="head">
                    <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('cost-overview::general.description') }}" />
                </x-slot>

                <x-slot name="body">
                    <input type="hidden" name="type" value="cost-overview" />

                    <x-form.group.contact type="customer" :contact="$cost_overview->contact" />

                    <x-form.group.text name="document_number" label="{{ trans('cost-overview::general.document_number') }}" :value="$cost_overview->document_number" />

                    <x-form.group.date name="issued_at" label="{{ trans('documents.issued_at') }}" icon="calendar_today" :value="$cost_overview->issued_at->toDateString()" show-date-selecter />

                    <x-form.group.date name="due_at" label="{{ trans('documents.due_at') }}" icon="calendar_today" :value="$cost_overview->due_at->toDateString()" show-date-selecter />

                    <x-form.group.currency :currency="$cost_overview->currency_code" />

                    <x-form.group.text name="budget" label="{{ trans('cost-overview::general.budget') }}" :value="$cost_overview->budget" />

                    <x-form.group.category type="income" :selected="$cost_overview->category_id" />
                </x-slot>
            </x-form.section>

            <x-form.section>
                <x-slot name="head">
                    <x-form.section.head title="{{ trans_choice('general.items', 2) }}" description="{{ trans('items.form_description.general') }}" />
                </x-slot>

                <x-slot name="body">
                    <x-form.group.item-list type="document" :document="$cost_overview" />
                </x-slot>
            </x-form.section>

            <x-form.section>
                <x-slot name="head">
                    <x-form.section.head title="{{ trans('general.others') }}" description="{{ trans('general.form_description.others') }}" />
                </x-slot>

                <x-slot name="body">
                    <x-form.group.textarea name="notes" label="{{ trans_choice('general.notes', 2) }}" :value="$cost_overview->notes" not-required />

                    <x-form.group.textarea name="footer" label="{{ trans('general.footer') }}" :value="$cost_overview->footer" not-required />

                    <x-form.input.hidden name="status" :value="$cost_overview->status" />
                </x-slot>
            </x-form.section>

            <x-form.section>
                <x-slot name="foot">
                    <x-form.buttons cancel-route="cost-overviews.index" />
                </x-slot>
            </x-form.section>
        </x-form>
    </x-slot>

    <x-script alias="cost-overview" file="cost-overviews" />
</x-layouts.admin>
