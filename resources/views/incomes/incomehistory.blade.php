<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Income History') }}
        </h2>
    </x-slot>

    <!-- Main Content Container -->
    <div class="py-10 flex items-center justify-center" style="background-color: #CCAAFF;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Text Container -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 max-w-md mx-auto p-4 text-center" style="background-color: rgba(255, 0, 255, 0.9);">
                @if (Auth::check())
                    <p>Welcome to the Income History side!</p>
                @else
                    <p>Please log in to see your profile.</p>
                @endif
            </div>

            <!-- Expense Table -->
            <div class="form-container mx-auto">
                @if($incomes->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No income history available.</p>
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
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incomes as $income)
                                    <tr class="text-gray-900 dark:text-gray-300">
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $income->item_id }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $income->item_name }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $income->quantity }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $income->description }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $income->time }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $income->date }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $income->amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                @endif
            </div>

            <!-- Back to Income Button -->
            <div class="p-4 button-container">
                <button type="button" class="back-btn shadow-lg">
                    <a href="{{ route('income.create') }}">Back</a>
                </button>
            </div>

            <style>
                .button-container {
                    display: flex;
                    justify-content: center;
                    margin-top: 10px;
                }

                .button-container button {
                    padding: 10px 20px;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    color: #fff;
                    width: auto;
                }

                .back-btn { background-color: #FF0000; }

                .button-container button:hover { opacity: 0.7; }
            </style>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>
