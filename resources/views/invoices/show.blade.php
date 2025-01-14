<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 py-10 px-6 flex flex-col items-center animate__animated animate__fadeInUp" style="background-color: #CCAAFF;">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg" style="background-color: rgba(255, 255, 255, 0.5);">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Invoice #{{ $invoice->invoice_number }}</h1>

            <p><strong>Client Name:</strong> {{ $invoice->client_name }}</p>
            <p><strong>Client Email:</strong> {{ $invoice->client_email }}</p>
            <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>

            <h2 class="text-lg font-semibold mt-4 mb-2">Items</h2>
            <table class="table-auto w-full border mb-6" style="background-color: rgba(255, 255, 255, 0.3);">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Unit Price</th>
                        <th class="border px-4 py-2">Total Unit Price</th>
                    </tr>
                </thead>
                @if($invoice->items && $invoice->items->isNotEmpty())
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="border px-4 py-2">{{ $item->description }}</td>
                                <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                <td class="border px-4 py-2">RM{{ number_format($item->unit_price, 2) }}</td>
                                <td class="border px-4 py-2">RM{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <tr>
                        <td colspan="4" class="text-center">No items found.</td>
                    </tr>
                @endif
            </table>

            <p><strong>Subtotal:</strong> RM{{ number_format($invoice->subtotal, 2) }}</p>
            <p><strong>Tax:</strong> {{ number_format($invoice->tax, 2) }}%</p>
            <p><strong>Total:</strong> RM{{ number_format($invoice->total, 2) }}</p>
        </div>

        <!-- Design Button -->
        <div class="py-6 button-container space-x-8">
            <button type="button" class="cancel-btn shadow-lg">
                <a href="{{ route('invoice') }}">Back</a>
            </button>

            <button type="button" class="history-btn shadow-lg">
                <a href="{{ route('invoices.design', $invoice->id) }}">Design Invoice</a>
            </button>
        </div>
    </div>

    <style>
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            width: 50%;
        }

        .cancel-btn { background-color: #d9534f; }
        .history-btn { background-color: #5bc0de; }

        .save-btn:hover {
            opacity: 0.7;
        }

        .button-container button:hover { opacity: 0.7; }
    </style>

    <!--Footer-->
    @include('components.footer')
</x-app-layout>
