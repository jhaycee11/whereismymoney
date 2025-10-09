<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring transactions and create them automatically on the specified day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $currentDay = $today->day;
        $currentMonth = $today->month;
        $currentYear = $today->year;
        
        $this->info("Processing recurring transactions for Day {$currentDay}...");
        
        // Get all active recurring transactions for today
        $recurringTransactions = RecurringTransaction::where('is_active', true)
            ->where('day_of_month', $currentDay)
            ->get();
        
        $processed = 0;
        $skipped = 0;
        
        foreach ($recurringTransactions as $recurring) {
            // Check if already executed this month
            if ($recurring->last_executed_at && 
                $recurring->last_executed_at->month === $currentMonth && 
                $recurring->last_executed_at->year === $currentYear) {
                $this->warn("  Skipped: {$recurring->category} (already processed this month)");
                $skipped++;
                continue;
            }
            
            // Create the transaction
            if ($recurring->type === 'expense') {
                Expense::create([
                    'user_id' => $recurring->user_id,
                    'category' => $recurring->category,
                    'amount' => $recurring->amount,
                    'expense_date' => $today,
                    'notes' => $recurring->notes . ' (Auto-generated)',
                ]);
                $this->info("  ✓ Created expense: {$recurring->category} - ¥{$recurring->amount}");
            } else {
                Income::create([
                    'user_id' => $recurring->user_id,
                    'source' => $recurring->category,
                    'amount' => $recurring->amount,
                    'income_date' => $today,
                    'notes' => $recurring->notes . ' (Auto-generated)',
                ]);
                $this->info("  ✓ Created income: {$recurring->category} - ¥{$recurring->amount}");
            }
            
            // Update last executed date
            $recurring->update(['last_executed_at' => $today]);
            $processed++;
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("  Processed: {$processed}");
        $this->info("  Skipped: {$skipped}");
        $this->newLine();
        
        return Command::SUCCESS;
    }
}
