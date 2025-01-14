<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Financial Report Management 📊') }}
        </h2>
    </x-slot>

    <div class="bg-purple-900 text-white flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFF;">
        
        <!-- Month Selection -->
        <form method="GET" action="{{ route('report') }}" class="mb-6 flex flex-row items-center gap-4 justify-center">
            <label for="month" class="text-gray-200 font-medium">Select Month:</label>
            <input type="month" id="month" name="month" value="{{ $selectedMonth }}" class="text-gray-800 p-2 rounded-lg border border-gray-300">
            <button type="submit" class="save-btn p-2 rounded-md shadow-lg px-4 flex items-center">
                Filter
                <img src="{{ asset('microscope.gif') }}" alt="Loading icon" class="ml-2" style="width: 16px; height: 16px;">
            </button>
        </form>


        <!-- Transaction Table -->
        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center mb-6 animate__animated animate__fadeInUp" style="background-color: rgba(255, 0, 255, 0.1);"> 
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Transactions of {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-600 text-gray-800">
                        <th class="py-2">Description</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-b border-gray-700">
                            <td class="py-2">{{ $transaction->description }}</td>
                            <td class="py-2" style="color: {{ $transaction->type === 'income' ? '#4CAF50' : '#F44336' }};">
                                {{ $transaction->type === 'income' ? '+' : '-' }} RM{{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="py-2">{{ $transaction->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-2 text-center text-gray-800">No transactions available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Section with Pie Chart and Balance -->
        <div class="p-3 flex space-x-8 mb-8">
            
            <!-- Pie Chart -->
            <div class="p-6 rounded-lg shadow-lg animate__animated animate__fadeInLeft" style="width: 256px; height: 256px; background-color: rgba(255, 0, 255, 0.2); display: flex; justify-content: center; align-items: center;">
                <canvas id="incomeExpenseChart"></canvas>
            </div>

            <!-- Total Balance -->
            <div class="p-6 rounded-lg shadow-lg w-48 h-48 flex flex-col items-center justify-center animate__animated animate__fadeInRight" style="background-color: rgba(255, 0, 255, 0.3);">
                <p class="text-sm font-semibold text-gray-800">Total Balance</p>
                <p class="text-3xl font-semibold">RM{{ number_format($totalBalance, 2) }}</p>
                <p class="{{ $balancePercentage >= 0 ? 'text-green-400' : 'text-red-400' }} text-sm font-semibold">
                    {{ $balancePercentage >= 0 ? '+' : '' }}{{ $balancePercentage }}%
                </p>
            </div>
            
        </div>
        
        <!-- Action Buttons -->
        <div class="p-4 button-container space-x-8">
            <button type="button" class="history-btn shadow-lg">
                <a href="{{ route('expense.history') }}" class="btn btn-secondary">Expense History</a>
            </button>
            <button type="button" class="history-btn shadow-lg">
                <a href="{{ route('income.history') }}" class="btn btn-primary">Income History</a>
            </button>
            <button type="button" class="history-btn shadow-lg">
                <a href="{{ route('invoice') }}" class="btn btn-primary">Invoice</a>
            </button>
        </div>

        <div class="p-4 flex flex-col items-center">
            <form method="POST" action="{{ route('sendMonthlyReport') }}" class="flex flex-row items-center gap-4 w-full justify-center">
                @csrf
                <!-- Month Selection -->
                <div class="flex flex-col items-start">
                    <label for="month" class="text-gray-700 font-medium">Select Month:</label>
                    <input type="month" id="month" name="month" class="text-gray-800 p-2 rounded-md border border-gray-300" required>
                </div>

                <!-- Email Input -->
                <div class="flex flex-col items-start">
                    <label for="email" class="text-gray-700 font-medium">Recipient Email:</label>
                    <input type="email" id="email" name="email" placeholder="example@example.com" class="text-gray-800 p-2 rounded-md border border-gray-300" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-6 save-btn p-2 rounded-md shadow-lg px-4 flex items-center">
                    Send Report
                    <img src="{{ asset('message.gif') }}" alt="Loading icon" class="ml-2" style="width: 16px; height: 16px;">
                </button>
            </form>

            <!-- Success Message -->
            @if(session('success'))
                <div class="rounded-lg shadow-lg text-gray-800 font-semibold p-2 w-full text-center mt-4" style="background-color: rgba(255, 0, 255, 0.3);">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Include Chart.js and Chart Configuration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const totalIncome = {{ $totalIncome }};
        const totalExpense = {{ $totalExpense }};

        const incomeExpenseData = {
            labels: ['Incomes', 'Expenses'],
            datasets: [{
                data: [totalIncome, totalExpense],
                backgroundColor: ['#4CAF50', '#F44336'],
                hoverBackgroundColor: ['#45A049', '#D32F2F']
            }]
        };

        const noRecordPlugin = {
            id: 'noRecordPlugin',
            beforeDraw: (chart) => {
                const { data } = chart;
                const hasData = data.datasets[0].data.some((value) => value > 0);

                if (!hasData) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;

                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#ffffff';
                    ctx.font = '16px sans-serif';
                    ctx.fillText('No Record', width / 2, height / 2);
                    ctx.restore();
                }
            }
        };

        const config = {
            type: 'doughnut',
            data: incomeExpenseData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { color: '#ffffff' }
                    },
                },
                cutout: '70%',
            },
            plugins: [noRecordPlugin]
        };

        const incomeExpenseChart = new Chart(
            document.getElementById('incomeExpenseChart').getContext('2d'),
            config
        );
    </script>

    <style>
        /* Social icons styling */
        .social-icons img {
            width: 20px;
            height: 20px;
            margin: 0 5px;
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
        width: 50%;
        }

        .cancel-btn { background-color: #d9534f; }
        .history-btn { background-color: #5bc0de; }
        .save-btn { background-color: #5cb85c; }

        .save-btn:hover {
            opacity: 0.7;
        }

        .button-container button:hover { opacity: 0.7; }
    </style>

    <!--Footer-->
    @include('components.footer')
</x-app-layout>