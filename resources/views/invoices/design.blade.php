<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Design Your Invoice') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 py-10 px-6 animate__animated animate__fadeInUp" style="background-color: #CCAAFF;">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Invoice Design</h1>

            <!-- Display Success or Error Messages -->
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display Invoice Data -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Invoice Details</h2>
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Client Name:</strong> {{ $invoice->client_name }}</p>
                <p><strong>Client Email:</strong> {{ $invoice->client_email }}</p>
                <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                <p><strong>Subtotal:</strong> RM{{ number_format($invoice->subtotal, 2) }}</p>
                <p><strong>Tax:</strong> {{ number_format($invoice->tax, 2) }}%</p>
                <p><strong>Total:</strong> RM{{ number_format($invoice->total, 2) }}</p>
            </div>

            <!-- Preview Section -->
            <h2 class="text-lg font-semibold mb-4">Preview Invoice</h2>
            <div id="template-preview" class="invoice-template {{ $invoice->template ?? 'default' }} mt-6 p-4 border rounded-lg">
                <h1 class="text-xl font-bold mb-2">Invoice: {{ $invoice->invoice_number }}</h1>
                <p>Client: {{ $invoice->client_name }}</p>
                <p>Email: {{ $invoice->client_email }}</p>
                <p>Date: {{ $invoice->invoice_date }}</p>
                <p>Due: {{ $invoice->due_date }}</p>
                <hr class="my-4 mt-2">
                <h2 class="font-semibold">Items</h2>
                <ul>
                    @foreach ($invoice->items as $item)
                        <li>{{ $item['description'] }} - {{ $item['quantity'] }} x RM{{ number_format($item['unit_price'], 2) }} = RM{{ number_format($item['total_unit_price'], 2) }}</li>
                    @endforeach
                </ul>
                <hr class="my-4 mt-2">
                <p><strong>Subtotal:</strong> RM{{ number_format($invoice->subtotal, 2) }}</p>
                <p><strong>Tax:</strong> {{ number_format($invoice->tax, 2) }}%</p>
                <hr class="my-4 mt-2">
                <p><strong>Total:</strong> RM{{ number_format($invoice->total, 2) }}</p>
                <hr class="my-4 mt-2">
                <h2 class="font-semibold">Beneficial</h2>
                <p>Sender: {{ Auth::user()->name }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
            </div>

            <!-- Design Section -->
            <h2 class="text-lg font-semibold mb-4 mt-8">Customize Your Invoice</h2>
            <form action="{{ route('invoices.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <!-- Choose Template & Customize Colors -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex flex-col flex-grow basis-[calc(50%-1rem)]">
                        <label for="template" class="block text-gray-800 dark:text-gray-200">Select Template</label>
                        <select id="template" name="template" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="default" {{ $invoice->template === 'default' ? 'selected' : '' }}>Default</option>
                            <option value="modern" {{ $invoice->template === 'modern' ? 'selected' : '' }}>Modern</option>
                            <option value="minimalist" {{ $invoice->template === 'minimalist' ? 'selected' : '' }}>Minimalist</option>
                        </select>
                    </div>

                    <div class="flex flex-col flex-grow basis-[calc(50%-1rem)]">
                        <label for="color" class="block text-gray-800 dark:text-gray-200">Primary Color</label>
                        <input type="color" id="color" name="color" 
                            value="{{ $invoice->primary_color ?? '#000000' }}" 
                            class="h-8 border rounded-lg">
                    </div>
                </div>

                <!-- Other Fields (hidden) -->
                <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}">
                <input type="hidden" name="client_name" value="{{ $invoice->client_name }}">
                <input type="hidden" name="client_email" value="{{ $invoice->client_email }}">
                <input type="hidden" name="invoice_date" value="{{ $invoice->invoice_date }}">
                <input type="hidden" name="due_date" value="{{ $invoice->due_date }}">
                <input type="hidden" name="subtotal" value="{{ $invoice->subtotal }}">
                <input type="hidden" name="tax" value="{{ $invoice->tax }}">
                <input type="hidden" name="total" value="{{ $invoice->total }}">
                @foreach ($invoice['items'] as $index => $item)
                    <input type="hidden" name="items[{{ $index }}][description]" value="{{ $item['description'] }}">
                    <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                    <input type="hidden" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] }}">
                    <input type="hidden" name="items[{{ $index }}][total_unit_price]" value="{{ $item['total_unit_price'] }}">
                @endforeach


                <!-- Preview Button -->
                <div class="py-6 button-container">
                    <button type="button" class="cancel-btn shadow-lg">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                    </button>

                    <button type="button" class="history-btn shadow-lg">
                        <a href="{{ route('invoice') }}" class="btn btn-primary">Invoice</a>
                    </button>

                    <button type="submit" class="save-btn shadow-lg">Preview Design</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/design.js') }}"></script>

    <!-- Styles -->
    <style>
        :root {
            --primary-color: {{ $invoice->primary_color ?? '#000000' }};
        }

        .invoice-template {
            padding: 20px;
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
    </style>

    <!--Footer-->
    @include('components.footer')
</x-app-layout>
