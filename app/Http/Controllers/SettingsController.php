<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display settings page with recurring transactions
     */
    public function index()
    {
        $user = Auth::user();
        
        $recurringTransactions = RecurringTransaction::where('user_id', $user->id)
            ->orderBy('type')
            ->orderBy('day_of_month')
            ->get();

        return view('dashboard.settings', compact('recurringTransactions'));
    }

    /**
     * Store a new recurring transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:9999999',
            'day_of_month' => 'required|integer|min:1|max:31',
            'notes' => 'nullable|string|max:1000',
        ], [
            'type.required' => 'Please select transaction type.',
            'category.required' => 'Please select category/source.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 1.',
            'day_of_month.required' => 'Day of month is required.',
            'day_of_month.min' => 'Day must be between 1 and 31.',
            'day_of_month.max' => 'Day must be between 1 and 31.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        RecurringTransaction::create($validated);

        return redirect()
            ->route('dashboard.settings')
            ->with('success', 'Recurring transaction added successfully!');
    }

    /**
     * Update recurring transaction
     */
    public function update(Request $request, RecurringTransaction $recurringTransaction)
    {
        // Authorization check
        if ($recurringTransaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'type' => 'required|in:expense,income',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:9999999',
            'day_of_month' => 'required|integer|min:1|max:31',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $recurringTransaction->update($validated);

        return redirect()
            ->route('dashboard.settings')
            ->with('success', 'Recurring transaction updated successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(RecurringTransaction $recurringTransaction)
    {
        // Authorization check
        if ($recurringTransaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $recurringTransaction->update([
            'is_active' => !$recurringTransaction->is_active
        ]);

        $status = $recurringTransaction->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('dashboard.settings')
            ->with('success', "Recurring transaction {$status} successfully!");
    }

    /**
     * Delete recurring transaction
     */
    public function destroy(RecurringTransaction $recurringTransaction)
    {
        // Authorization check
        if ($recurringTransaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $recurringTransaction->delete();

        return redirect()
            ->route('dashboard.settings')
            ->with('success', 'Recurring transaction deleted successfully!');
    }

    /**
     * Get recurring transaction data for editing (AJAX endpoint)
     */
    public function show(RecurringTransaction $recurringTransaction)
    {
        // Authorization check
        if ($recurringTransaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json($recurringTransaction);
    }
}
