<x-app
    title="Thanks for your purchase!"
    :hide-top-ad="true"
>
    <x-heroicon-o-check-circle class="mx-auto mb-2 text-green-600 size-12" />

    <x-section
        title="Thanks for your purchase!"
        class="md:max-w-(--breakpoint-sm)"
    >
        <table class="w-full">
            <tr class="border-b border-gray-200">
                <th class="p-2 pl-0 text-left md:pl-0 md:p-4">Product</th>
                <th class="p-2 text-right md:p-4">Quantity</th>
                <th class="p-2 pr-0 text-right md:pr-0 md:p-4">Price</th>
            </tr>

            @foreach ($session->line_items->data as $item)
                <tr class="border-b border-gray-200">
                    <td class="p-2 pl-0 md:pl-0 md:p-4">
                        <p class="font-medium">{{ $item->price->product->name }}</p>
                        <p class="mt-1 text-gray-500">{{ $item->price->product->description }}</p>
                    </td>

                    <td class="p-2 text-right align-top md:p-4">
                        <p>{{ $item->quantity }}</p>
                    </td>

                    <td class="p-2 pr-0 align-top md:pr-0 md:p-4">
                        {{ Number::currency(($item->amount_total ?? 0) / 100, $item->currency ?? $session->currency ?? 'USD') }}
                    </td>
                </tr>
            @endforeach

            <tr class="border-b border-gray-200">
                <th class="p-2 pl-0 text-left md:pl-0 md:p-4" colspan="2">
                    Subtotal
                </th>

                <td class="p-2 pr-0 text-right md:pr-0 md:p-4">
                    {{ Number::currency(($session->amount_subtotal ?? 0) / 100, $session->currency ?? 'USD') }}
                </td>
            </tr>

            <tr class="border-b border-gray-200">
                <th class="p-2 pl-0 text-left md:pl-0 md:p-4" colspan="2">
                    Tax
                </th>

                <td class="p-2 pr-0 text-right md:pr-0 md:p-4">
                    {{ Number::currency(($session->total_details->amount_tax ?? 0) / 100, $session->currency ?? 'USD') }}
                </td>
            </tr>

            <tr class="border-b border-gray-200">
                <th class="p-2 pl-0 text-left md:pl-0 md:p-4" colspan="2">
                    Total
                </th>

                <td class="p-2 pr-0 text-right md:pr-0 md:p-4">
                    {{ Number::currency(($session->amount_total ?? 0) / 100, $session->currency ?? 'USD') }}
                </td>
            </tr>
        </table>

        @if (! empty($email = $session->customer_details?->email))
            <p class="mt-12">
                A receipt has been sent to {{ $email }}.
            </p>
        @endif

        <p>If you have any questions, please get in touch at <a href="mailto:hello@benjamincrozat.com" class="font-medium underline">hello@benjamincrozat.com</a>.</p>

        <x-btn
            primary-alt
            href="{{ $session->invoice?->hosted_invoice_url }}"
            target="_blank"
            class="table mx-auto mt-4"
        >
            Download invoice
        </x-btn>
    </x-section>

    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>

    <script>
        new JSConfetti().addConfetti()
    </script>
</x-app>
