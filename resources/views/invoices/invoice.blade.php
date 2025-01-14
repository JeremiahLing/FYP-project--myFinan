<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invoice Management') }}
        </h2>
    </x-slot>

    <div class="bg-purple-900 text-white flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFF;">
        <div class="button-container mb-6">
            <button type="button" class="history-btn shadow-lg">
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    Create New Invoice
                </a>
            </button>
        </div>

        <div class="container mb-6 mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.5);">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Invoice List</h1>

            @if($invoices->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No invoices found.</p>
            @else
                <!-- Table -->
                <table class="table-auto w-full text-left">
                    <thead class="bg-gray-100 dark:bg-gray-700 animate__animated animate__fadeInUp">
                        <tr>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Invoice #</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Client</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Date</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Due Date</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="window.location='{{ route('invoices.show', $invoice->id) }}'">
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->invoice_number }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->client_name }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->invoice_date }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->due_date }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">RM{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Footer -->
                <div class="mt-6">
                    <p class="text-gray-600 dark:text-gray-400">Total Invoices: {{ $invoices->count() }}</p>
                </div>
            @endif
        </div>

        <style>
            table tbody tr {
                transition: background-color 0.3s ease;
            }

            table tbody tr:hover {
                background-color: #f0f0f0;
                cursor: pointer;
            }

            .button-container {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
            }

            .button-container button {
                padding: 10px 15px;
                font-size: 16px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                color: #fff;
                width: 100%;
            }

            .history-btn { background-color: #5bc0de; }

            .button-container button:hover { opacity: 0.7; }
        </style>
    </div>

    <!--footer-->
    @include('components.footer')
</x-app-layout>
