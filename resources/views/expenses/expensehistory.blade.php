<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Expense History') }}
        </h2>
    </x-slot>

    <!-- Main Content Container -->
    <div class="min-h-screen py-10 w-full flex justify-center" style="background-color: #CCAAFF;">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Text Container -->
            <div class="overflow-hidden shadow-sm sm:rounded-lg mb-6 max-w-md mx-auto p-6 text-center animate__animated animate__fadeInDown" style="background-color: rgba(255, 0, 255, 0.5);">
                @if (Auth::check())
                    <p>Welcome to the Expense History side!</p>
                @else
                    <p>Please log in to see your profile.</p>
                @endif
            </div>

            <!--Search bar-->
            <div class="mb-4 animate__animated animate__fadeInUp">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search Expense items..."
                    class="block w-1/3 px-4 py-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                >
            </div>

            <!-- Expense Table -->
            <div class="form-container mx-auto animate__animated animate__fadeInUp">
                @if($expenses->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No expense history available.</p>
                @else
                    <table class="min-w-full table-auto border-collapse border border-gray-200 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Item ID</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Item Name</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Quantity</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Description</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Time</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Date</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Amount (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr class="text-gray-900 dark:text-gray-300" data-id="{{ $expense->item_id }}">
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->item_id }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->item_name }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->quantity }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $expense->description }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->time }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->date }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                @endif

                <!-- Back to Expense Button -->
                <div class="p-4 button-container">
                    <button type="button" class="cancel-btn shadow-lg">
                        <a href="{{ route('expense.create') }}">Back</a>
                    </button>
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editExpenseModal" class="fixed z-10 inset-0 overflow-y-auto hidden animate__animated animate__fadeInUp" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="flex items-center justify-center min-h-screen px-4 py-12">
                    <div class="modal-content bg-white dark:bg-gray-800 shadow-lg rounded-lg max-w-lg w-full p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Edit Expense Item</h2>
                        <form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('expense.update') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="item_id" id="editItemId">

                            <div class="mb-4">
                                <label for="editItemName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                                <input type="text" name="item_name" id="editItemName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>

                            <div class="mb-4">
                                <label for="editQuantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                <input type="number" name="quantity" id="editQuantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>

                            <div class="mb-4">
                                <label for="editDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="editTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                                    <div class="time-container">
                                        <input type="time" name="time" id="editTime" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                                <div>
                                    <label for="editDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                    <div class="date-container">
                                        <input type="date" name="date" id="editDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                                <div>
                                    <label for="editAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (RM)</label>
                                    <input type="number" name="amount" id="editAmount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('editExpenseModal');
            const cancelEdit = document.getElementById('cancelEdit');
            const modalContent = modal.querySelector('.modal-content');
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');
            const editForm = document.getElementById('editForm');

            editForm.addEventListener('submit', (event) => {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(editForm);

                fetch(editForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert(data.message || 'Expense item updated successfully.');
                            modal.classList.add('hidden'); // Close modal

                            // Optionally, update the table row without refreshing
                            const updatedRow = document.querySelector(`tbody tr[data-id="${formData.get('item_id')}"]`);
                            if (updatedRow) {
                                updatedRow.querySelector('td:nth-child(2)').textContent = formData.get('item_name');
                                updatedRow.querySelector('td:nth-child(3)').textContent = formData.get('quantity');
                                updatedRow.querySelector('td:nth-child(4)').textContent = formData.get('description');
                                updatedRow.querySelector('td:nth-child(5)').textContent = formData.get('time');
                                updatedRow.querySelector('td:nth-child(6)').textContent = formData.get('date');
                                updatedRow.querySelector('td:nth-child(7)').textContent = formData.get('amount');
                            }
                        } else {
                            alert(data.message || 'Failed to update Expense item.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });

            // Open modal on row click
            document.querySelectorAll('tbody tr').forEach(row => {
                row.addEventListener('click', () => {
                    const itemId = row.querySelector('td:nth-child(1)').textContent.trim();
                    const itemName = row.querySelector('td:nth-child(2)').textContent.trim();
                    const quantity = row.querySelector('td:nth-child(3)').textContent.trim();
                    const description = row.querySelector('td:nth-child(4)').textContent.trim();
                    const time = row.querySelector('td:nth-child(5)').textContent.trim();
                    const date = row.querySelector('td:nth-child(6)').textContent.trim();
                    const amount = row.querySelector('td:nth-child(7)').textContent.trim();

                    // Populate modal fields
                    document.getElementById('editItemId').value = itemId;
                    document.getElementById('editItemName').value = itemName;
                    document.getElementById('editQuantity').value = quantity;
                    document.getElementById('editDescription').value = description;
                    document.getElementById('editTime').value = time;
                    document.getElementById('editDate').value = date;
                    document.getElementById('editAmount').value = amount;

                    modal.classList.remove('hidden'); // Show modal
                });
            });

            // Close modal on Cancel button click
            cancelEdit.addEventListener('click', () => {
                modal.classList.add('hidden'); // Hide modal
            });

            // Close modal when clicking outside the content
            modal.addEventListener('click', (event) => {
                if (event.target === modal) { // Ensure the click is outside the modal content
                    modal.classList.add('hidden');
                }
            });

            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase();

                    tableRows.forEach(row => {
                        const itemName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const description = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                        if (itemName.includes(query) || description.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>
