<!DOCTYPE html>
<html>
<head>
    <title>Monthly Financial Report</title>
</head>
<body>
    <h1>Monthly Financial Report - {{ $month }}</h1>
    <p>Total Income: RM{{ number_format($totalIncome, 2) }}</p>
    <p>Total Expense: RM{{ number_format($totalExpense, 2) }}</p>
    <p>Balance: RM{{ number_format($totalBalance, 2) }}</p>

    <h2>Transactions</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->description }}</td>
                    <td style="color: {{ $transaction->type === 'income' ? 'green' : 'red' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }} RM{{ number_format($transaction->amount, 2) }}
                    </td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
