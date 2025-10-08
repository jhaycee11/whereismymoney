<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get monthly totals
        $totalExpenses = Expense::where('user_id', $user->id)
            ->whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->sum('amount');

        $totalIncome = Income::where('user_id', $user->id)
            ->whereMonth('income_date', $currentMonth)
            ->whereYear('income_date', $currentYear)
            ->sum('amount');

        $netBalance = $totalIncome - $totalExpenses;

        // Get recent expenses (last 5)
        $recentExpenses = Expense::where('user_id', $user->id)
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get expense breakdown by category
        $expenseByCategory = Expense::where('user_id', $user->id)
            ->whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('dashboard.index', compact(
            'totalExpenses',
            'totalIncome',
            'netBalance',
            'recentExpenses',
            'expenseByCategory'
        ));
    }

    public function expenses()
    {
        $user = Auth::user();
        $expenses = Expense::where('user_id', $user->id)
            ->orderBy('expense_date', 'desc')
            ->paginate(20);

        return view('dashboard.expenses', compact('expenses'));
    }

    public function income()
    {
        $user = Auth::user();
        $incomes = Income::where('user_id', $user->id)
            ->orderBy('income_date', 'desc')
            ->paginate(20);

        return view('dashboard.income', compact('incomes'));
    }

    public function history()
    {
        $user = Auth::user();
        
        // Combine expenses and incomes
        $expenses = Expense::where('user_id', $user->id)
            ->get()
            ->map(function ($expense) {
                return [
                    'date' => $expense->expense_date,
                    'type' => 'expense',
                    'category' => $expense->category,
                    'amount' => $expense->amount,
                    'created_at' => $expense->created_at,
                ];
            });

        $incomes = Income::where('user_id', $user->id)
            ->get()
            ->map(function ($income) {
                return [
                    'date' => $income->income_date,
                    'type' => 'income',
                    'category' => $income->source,
                    'amount' => $income->amount,
                    'created_at' => $income->created_at,
                ];
            });

        // Sort by date (oldest first) to calculate running balance
        $transactions = $expenses->concat($incomes)
            ->sortBy('date')
            ->sortBy('created_at')
            ->values();

        // Calculate running balance
        $runningBalance = 0;
        $transactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction['type'] === 'income') {
                $runningBalance += $transaction['amount'];
            } else {
                $runningBalance -= $transaction['amount'];
            }
            $transaction['running_balance'] = $runningBalance;
            return $transaction;
        });

        // Reverse to show newest first
        $transactions = $transactions->reverse()->values();

        return view('dashboard.history', compact('transactions'));
    }
}
