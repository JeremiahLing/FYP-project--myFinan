<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Preview Your Invoice') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 py-10 px-6" style="background-color: #CCAAFF;">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg" style="background-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Invoice Preview</h1>

            <div id="template-preview" class="invoice-template {{ $validated['template'] ?? 'default' }} p-6 border rounded-lg">
                <img src="{{ $validated['logo'] ?? '/favicon.ico' }}" class="w-16 h-16 object-cover rounded-lg mb-4">
                <h1 class="text-xl font-bold mb-2">Invoice: {{ $validated['invoice_number'] }}</h1>
                <p>Client: {{ $validated['client_name'] }}</p>
                <p>Email: {{ $validated['client_email'] }}</p>
                <p>Date: {{ $validated['invoice_date'] }}</p>
                <p>Due: {{ $validated['due_date'] }}</p>
                <hr class="my-4 mt-2">
                <h2 class="font-semibold">Items</h2>
                <ul>
                    @foreach ($validated['items'] as $item)
                        <li>
                            {{ $item['description'] }} - {{ $item['quantity'] }} x RM{{ number_format($item['unit_price'], 2) }} = 
                            RM{{ number_format($item['total_unit_price'], 2) }}
                        </li>
                    @endforeach
                </ul>
                <hr class="my-4 mt-2">
                <p><strong>Subtotal:</strong> RM{{ number_format($validated['subtotal'], 2) }}</p>
                <p><strong>Tax:</strong> {{ number_format($validated['tax'], 2) }}%</p>
                <hr class="my-4 mt-2">
                <p><strong>Total:</strong> RM{{ number_format($validated['total'], 2) }}</p>
                <hr class="my-4 mt-2">
                <h2 class="font-semibold">Beneficial</h2>
                <p>Sender: {{ Auth::user()->name }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
            </div>

            <div class="py-6 flex justify-between">
                <a href="{{ url()->previous() }}" class="btn cancel-btn shadow-lg">Back</a>
                <a href="{{ route('invoice') }}" class="btn history-btn shadow-lg">Invoice</a>

                <form action="{{ route('invoice.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $validated['invoice_id'] }}">
                    <input type="hidden" name="client_email" value="{{ $validated['client_email'] }}">
                    

                    <button type="submit" class="btn save-btn shadow-lg">Send</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        :root {
            --primary-color: {{ $validated['color'] ?? '#000000' }};
        }

        .invoice-template {
            background-color: #fff;
            color: var(--primary-color);
        }

        .invoice-template.modern {
            background-color: #f0f0f0;
            border-left: 4px solid var(--primary-color);
        }

        .invoice-template.minimalist {
            background: linear-gradient(to bottom, #ffffff, #e6e6e6);
            border: 1px solid var(--primary-color);
        }

        .btn {
            padding: 10px 0;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            width: 120px;
            text-align: center;
            text-decoration: none;
        }

        .cancel-btn { background-color: #d9534f; }
        .history-btn { background-color: #5bc0de; }
        .save-btn { background-color: #5cb85c; }

        .btn:hover { opacity: 0.7; }
    </style>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>
