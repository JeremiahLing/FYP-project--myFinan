<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Mail\MonthlyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function showReport(Request $request)
    {
        // Parse the selected month into start and end dates
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // Filter incomes and expenses by the user ID and selected month
        $userId = Auth::id();
        $incomes = Income::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $expenses = Expense::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Calculate total income and expense amounts
        $totalIncome = $incomes->sum('amount') ?? 0;
        $totalExpense = $expenses->sum('amount') ?? 0;

        // Calculate total balance and balance percentage
        $totalBalance = $totalIncome - $totalExpense;
        $balancePercentage = ($totalIncome > 0) ? round(($totalBalance / $totalIncome) * 100, 2) : 0;

        // Combine incomes and expenses into a single transaction-like collection
        $transactions = $incomes->map(function ($income) {
            $income->type = 'income';
            return $income;
        })->merge(
            $expenses->map(function ($expense) {
                $expense->type = 'expense';
                return $expense;
            })
        )->sortByDesc('created_at')->values(); // Sort by creation date, newest first

        // Pass the filtered data and selected month to the view
        return view('report', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalBalance' => $totalBalance,
            'balancePercentage' => $balancePercentage,
            'selectedMonth' => $month
        ]);
    }

    public function sendMonthlyReport(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'month' => 'required|date_format:Y-m',
        ]);

        // Get the selected month from the request
        $month = $request->input('month'); // Format: 'YYYY-MM'

        // Filter incomes and expenses for the selected month
        $incomes = Income::whereYear('created_at', substr($month, 0, 4))
                        ->whereMonth('created_at', substr($month, 5, 2))
                        ->get();
        $expenses = Expense::whereYear('created_at', substr($month, 0, 4))
                        ->whereMonth('created_at', substr($month, 5, 2))
                        ->get();

        // Combine incomes and expenses into a single collection
        $transactions = $incomes->map(function ($income) {
            $income->type = 'income';
            return $income;
        })->merge(
            $expenses->map(function ($expense) {
                $expense->type = 'expense';
                return $expense;
            })
        )->sortByDesc('created_at')->values();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        // Send the email
        try {
            Mail::to($request->input('email'))->send(new MonthlyReportMail(
                $month,
                $transactions,
                $totalIncome,
                $totalExpense,
                $totalBalance
            ));
            return back()->with('success', 'Monthly report sent successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send the monthly report: ' . $e->getMessage()]);
        }        
    }
}
