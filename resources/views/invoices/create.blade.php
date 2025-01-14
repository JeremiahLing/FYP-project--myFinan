<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Invoice') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 text-black flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFF;">
        <div class="mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">New Invoice</h1>

            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <!-- Invoice Number -->
                <div class="mb-4">
                    <label for="invoice_number" class="block text-gray-800 dark:text-gray-200">Invoice Number</label>
                    <input type="text" id="invoice_number" name="invoice_number" value="{{ $nextId }}" readonly>
                </div>

                <!-- Client Details -->
                <div class="mb-4">
                    <label for="client_name" class="block text-gray-800 dark:text-gray-200">Client Name</label>
                    <input type="text" id="client_name" name="client_name" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>
                <div class="mb-4">
                    <label for="client_email" class="block text-gray-800 dark:text-gray-200">Client Email</label>
                    <input type="email" id="client_email" name="client_email" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <!-- Dates -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex flex-col flex-grow basis-[calc(50%-1rem)]">
                        <label for="invoice_date" class="block text-gray-800 dark:text-gray-200">Invoice Date</label>
                        <input type="date" id="invoice_date" name="invoice_date" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <div class="flex flex-col flex-grow basis-[calc(50%-1rem)]">
                        <label for="due_date" class="block text-gray-800 dark:text-gray-200">Due Date</label>
                        <input type="date" id="due_date" name="due_date" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                </div>


                <!-- Items -->
                <div class="mb-4">
                    <label class="block text-gray-800 dark:text-gray-200">Items</label>
                    <table class="w-full border-collapse border border-gray-300 dark:border-gray-700" id="items-table">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Description</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Quantity</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Unit Price (RM)</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Total Unit Price (RM)</th>
                                <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-700">
                                    <input type="text" name="items[0][description]" class="w-full px-2 py-1 border rounded-lg" required>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-700">
                                    <input type="number" name="items[0][quantity]" class="w-full px-2 py-1 border rounded-lg quantity" value="0" min="1" required>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-700">
                                    <input type="number" name="items[0][unit_price]" class="w-full px-2 py-1 border rounded-lg unit-price" value="0.00" step="0.01" required>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-700">
                                    <input type="number" name="items[0][total_unit_price]" class="w-full px-2 py-1 border rounded-lg total_unit_price" value="0.00" readonly>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-700 text-center">
                                    <button type="button" class="bg-red-500 text-red-500 px-3 py-1 rounded-lg remove-item">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="add-item" class="mt-2 bg-green-500 text-green-600 px-4 py-2 rounded-lg">Add Item</button>
                </div>

                <!-- Financials -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="subtotal" class="block text-gray-800 dark:text-gray-200">Subtotal (RM)</label>
                        <input type="number" id="subtotal" name="subtotal" value="0.00"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               readonly>
                    </div>
                    <div>
                        <label for="tax" class="block text-gray-800 dark:text-gray-200">Tax (%)</label>
                        <input type="number" id="tax" name="tax" step="0.01" value="6"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="total" class="block text-gray-800 dark:text-gray-200">Total (RM)</label>
                        <input type="number" id="total" name="total" value="0.00"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               readonly>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="p-4 button-container">
                    <button type="button" class="cancel-btn shadow-lg">
                        <a href="{{ route('invoice') }}" class="btn btn-primary">Back</a>
                    </button>

                    <form method="POST" action="{{ route('invoice') }}">
                        @csrf
                        <!-- Include all necessary fields -->
                        <button type="submit" class="save-btn shadow-lg">Save</button>
                    </form>
                </div>
            </form>
        </div>
    </div>

    <style>
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
        width: 15%;
        }

        .cancel-btn { background-color: #d9534f; }
        .save-btn { background-color: #5cb85c; }

        .save-btn:hover {
            opacity: 0.7;
        }

        .button-container button:hover { opacity: 0.7; }
    </style>
    
    <!--Footer-->
    @include('components.footer')

    <script>
        // Add a new item row
        document.getElementById('add-item').addEventListener('click', function () {
            const table = document.getElementById('items-table').querySelector('tbody');
            const rowCount = table.rows.length;
            const newRow = `
                <tr>
                    <td class="border border-gray-300 dark:border-gray-700">
                        <input type="text" name="items[${rowCount}][description]" class="w-full px-2 py-1 border rounded-lg" required>
                    </td>
                    <td class="border border-gray-300 dark:border-gray-700">
                        <input type="number" name="items[${rowCount}][quantity]" class="w-full px-2 py-1 border rounded-lg quantity" value="0" min="1" required>
                    </td>
                    <td class="border border-gray-300 dark:border-gray-700">
                        <input type="number" name="items[${rowCount}][unit_price]" class="w-full px-2 py-1 border rounded-lg unit-price" value="0.00" step="0.01" required>
                    </td>
                    <td class="border border-gray-300 dark:border-gray-700">
                        <input type="number" name="items[${rowCount}][total_unit_price]" class="w-full px-2 py-1 border rounded-lg total_unit_price" value="0.00" readonly>
                    </td>
                    <td class="border border-gray-300 dark:border-gray-700 text-center">
                        <button type="button" class="bg-red-500 text-red-500 px-3 py-1 rounded-lg remove-item">Remove</button>
                    </td>
                </tr>
            `;
            table.insertAdjacentHTML('beforeend', newRow);
        });

        // Remove an item row
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('tr').remove();
                updateInvoiceTotals();
            }
        });

        // Calculate subtotal for each row
        const itemsTable = document.getElementById('items-table');
        itemsTable.addEventListener('input', function (event) {
            if (event.target.classList.contains('quantity') || event.target.classList.contains('unit-price')) {
                const row = event.target.closest('tr');
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                const total_unit_price = row.querySelector('.total_unit_price');
                total_unit_price.value = (quantity * unitPrice).toFixed(2);
                updateInvoiceTotals();
            }
        });

        // Update invoice totals
        function updateInvoiceTotals() {
            const subtotals = document.querySelectorAll('.total_unit_price');
            let subtotalSum = 0;

            subtotals.forEach(total_unit_price => {
                subtotalSum += parseFloat(total_unit_price.value) || 0;
            });

            const taxRate = parseFloat(document.getElementById('tax').value) || 0;
            const taxAmount = (subtotalSum * taxRate) / 100;
            const totalAmount = subtotalSum + taxAmount;

            document.getElementById('subtotal').value = subtotalSum.toFixed(2);
            document.getElementById('total').value = totalAmount.toFixed(2);
        }

        // Update totals when tax changes
        document.getElementById('tax').addEventListener('input', function () {
            updateInvoiceTotals();
        });
    </script>
</x-app-layout>