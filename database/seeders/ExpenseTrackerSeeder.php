<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ExpenseTrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update demo user
        $user = User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        // Clear existing demo data to prevent duplicates on re-seeding
        $user->expenses()->delete();
        $user->incomes()->delete();

        // Categories for expenses
        $categories = ['Food', 'Transport', 'Entertainment', 'Utilities', 'Shopping', 'Healthcare'];
        
        // Sample expense descriptions
        $expenseDescriptions = [
            'Food' => ['Grocery shopping', 'Restaurant dinner', 'Coffee shop', 'Fast food lunch', 'Delivery order'],
            'Transport' => ['Gas refill', 'Uber ride', 'Public transport', 'Parking fee', 'Car maintenance'],
            'Entertainment' => ['Movie tickets', 'Concert tickets', 'Streaming subscription', 'Gaming', 'Books'],
            'Utilities' => ['Electricity bill', 'Water bill', 'Internet bill', 'Phone bill', 'Gas bill'],
            'Shopping' => ['Clothing', 'Electronics', 'Home decor', 'Accessories', 'Gifts'],
            'Healthcare' => ['Doctor visit', 'Pharmacy', 'Gym membership', 'Vitamins', 'Medical tests'],
        ];

        // Sample notes for expenses (some have notes, some don't)
        $sampleNotes = [
            null,
            null,
            null,
            'Paid with credit card',
            'Need to reimburse',
            'Business expense',
            'Split with roommate',
            'Monthly recurring',
            'Special occasion',
            'Emergency purchase',
        ];

        // Create expenses for the current month
        $currentMonth = Carbon::now();
        for ($i = 0; $i < 30; $i++) {
            $category = $categories[array_rand($categories)];
            $description = $expenseDescriptions[$category][array_rand($expenseDescriptions[$category])];
            
            Expense::create([
                'user_id' => $user->id,
                'category' => $category,
                'description' => $description,
                'amount' => rand(500, 15000) / 100, // Random amount between $5 and $150
                'expense_date' => $currentMonth->copy()->subDays(rand(0, 29)),
                'notes' => $sampleNotes[array_rand($sampleNotes)],
            ]);
        }

        // Create some expenses from previous months
        for ($i = 0; $i < 20; $i++) {
            $category = $categories[array_rand($categories)];
            $description = $expenseDescriptions[$category][array_rand($expenseDescriptions[$category])];
            
            Expense::create([
                'user_id' => $user->id,
                'category' => $category,
                'description' => $description,
                'amount' => rand(500, 15000) / 100,
                'expense_date' => $currentMonth->copy()->subMonths(rand(1, 3))->subDays(rand(0, 29)),
                'notes' => $sampleNotes[array_rand($sampleNotes)],
            ]);
        }

        // Income sources
        $incomeSources = ['Salary', 'Freelance', 'Investment', 'Bonus', 'Gift'];
        $incomeDescriptions = [
            'Salary' => ['Monthly salary', 'Paycheck', 'Commission'],
            'Freelance' => ['Project payment', 'Consulting fee', 'Design work'],
            'Investment' => ['Stock dividend', 'Interest payment', 'Rental income'],
            'Bonus' => ['Performance bonus', 'Year-end bonus', 'Referral bonus'],
            'Gift' => ['Birthday gift', 'Holiday gift', 'Family gift'],
        ];

        // Create income for the current month
        for ($i = 0; $i < 5; $i++) {
            $source = $incomeSources[array_rand($incomeSources)];
            $description = $incomeDescriptions[$source][array_rand($incomeDescriptions[$source])];
            
            Income::create([
                'user_id' => $user->id,
                'source' => $source,
                'description' => $description,
                'amount' => rand(50000, 500000) / 100, // Random amount between $500 and $5000
                'income_date' => $currentMonth->copy()->subDays(rand(0, 29)),
            ]);
        }

        // Create income from previous months
        for ($i = 0; $i < 10; $i++) {
            $source = $incomeSources[array_rand($incomeSources)];
            $description = $incomeDescriptions[$source][array_rand($incomeDescriptions[$source])];
            
            Income::create([
                'user_id' => $user->id,
                'source' => $source,
                'description' => $description,
                'amount' => rand(50000, 500000) / 100,
                'income_date' => $currentMonth->copy()->subMonths(rand(1, 3))->subDays(rand(0, 29)),
            ]);
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Demo User Credentials:');
        $this->command->info('Email: demo@example.com');
        $this->command->info('Password: password');
    }
}
