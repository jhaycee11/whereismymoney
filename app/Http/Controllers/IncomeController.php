<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncomeController extends Controller
{
    /**
     * Display a listing of incomes with search and filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Income::where('user_id', $user->id);

        // Description search
        if ($request->filled('description_search')) {
            $query->where('description', 'like', "%{$request->description_search}%");
        }

        // Amount search
        if ($request->filled('amount_search')) {
            $query->where('amount', 'like', "%{$request->amount_search}%");
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('income_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('income_date', '<=', $request->end_date);
        }

        // Source filter
        if ($request->filled('source') && $request->source !== 'all') {
            $query->where('source', $request->source);
        }

        $incomes = $query->orderBy('income_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20)
                          ->withQueryString();

        // Get all unique sources for filter dropdown
        $sources = Income::where('user_id', $user->id)
                         ->distinct()
                         ->pluck('source')
                         ->sort()
                         ->values();

        return view('dashboard.income', compact('incomes', 'sources'));
    }

    /**
     * Store a newly created income.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:1|max:9999999',
            'income_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ], [
            'source.required' => 'Please enter an income source.',
            'description.required' => 'Description is required.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 1.',
            'income_date.required' => 'Income date is required.',
            'income_date.before_or_equal' => 'Income date cannot be in the future.',
        ]);

        $validated['user_id'] = Auth::id();

        Income::create($validated);

        return redirect()
            ->route('dashboard.income')
            ->with('success', 'Income added successfully!');
    }

    /**
     * Update the specified income.
     */
    public function update(Request $request, Income $income)
    {
        // Authorization check
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:1|max:9999999',
            'income_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ], [
            'source.required' => 'Please enter an income source.',
            'description.required' => 'Description is required.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 1.',
            'income_date.required' => 'Income date is required.',
            'income_date.before_or_equal' => 'Income date cannot be in the future.',
        ]);

        $income->update($validated);

        return redirect()
            ->route('dashboard.income')
            ->with('success', 'Income updated successfully!');
    }

    /**
     * Remove the specified income.
     */
    public function destroy(Income $income)
    {
        // Authorization check
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $income->delete();

        return redirect()
            ->route('dashboard.income')
            ->with('success', 'Income deleted successfully!');
    }

    /**
     * Get income data for editing (AJAX endpoint).
     */
    public function show(Income $income)
    {
        // Authorization check
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json($income);
    }
}

