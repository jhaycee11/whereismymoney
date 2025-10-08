<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses with search and filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Expense::where('user_id', $user->id);

        // Amount search
        if ($request->filled('amount_search')) {
            $query->where('amount', 'like', "%{$request->amount_search}%");
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20)
                          ->withQueryString();

        // Get all unique categories for filter dropdown
        $categories = Expense::where('user_id', $user->id)
                             ->distinct()
                             ->pluck('category')
                             ->sort()
                             ->values();

        return view('dashboard.expenses', compact('expenses', 'categories'));
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:9999999',
            'expense_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ], [
            'category.required' => 'Please select a category.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 1.',
            'expense_date.required' => 'Expense date is required.',
            'expense_date.before_or_equal' => 'Expense date cannot be in the future.',
        ]);

        $validated['user_id'] = Auth::id();

        Expense::create($validated);

        return redirect()
            ->route('dashboard.expenses')
            ->with('success', 'Expense added successfully!');
    }

    /**
     * Update the specified expense.
     */
    public function update(Request $request, Expense $expense)
    {
        // Authorization check
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:9999999',
            'expense_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ], [
            'category.required' => 'Please select a category.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 1.',
            'expense_date.required' => 'Expense date is required.',
            'expense_date.before_or_equal' => 'Expense date cannot be in the future.',
        ]);

        $expense->update($validated);

        return redirect()
            ->route('dashboard.expenses')
            ->with('success', 'Expense updated successfully!');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(Expense $expense)
    {
        // Authorization check
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->delete();

        return redirect()
            ->route('dashboard.expenses')
            ->with('success', 'Expense deleted successfully!');
    }

    /**
     * Get expense data for editing (AJAX endpoint).
     */
    public function show(Expense $expense)
    {
        // Authorization check
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json($expense);
    }
}

