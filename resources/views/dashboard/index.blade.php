@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard</h2>
        <p class="text-xs sm:text-sm text-gray-600">Welcome back! Here's your financial overview for this month.</p>
    </div>

    <!-- Monthly Overview Cards -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total Expenses Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Expenses</p>
                    <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold text-gray-900 truncate">¥{{ number_format($totalExpenses, 0) }}</p>
                    <p class="mt-1 text-xs text-gray-500">This month</p>
                </div>
                <div class="flex-shrink-0 p-2 sm:p-3 bg-red-50 rounded-full">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Income Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Income</p>
                    <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold text-gray-900 truncate">¥{{ number_format($totalIncome, 0) }}</p>
                    <p class="mt-1 text-xs text-gray-500">This month</p>
                </div>
                <div class="flex-shrink-0 p-2 sm:p-3 bg-green-50 rounded-full">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Net Balance Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Net Balance</p>
                    <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }} truncate">
                        ¥{{ number_format(abs($netBalance), 0) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">Income - Expenses</p>
                </div>
                <div class="flex-shrink-0 p-2 sm:p-3 bg-indigo-50 rounded-full">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
        <!-- Recent Expenses History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Recent Expenses</h3>
                    <a href="{{ route('dashboard.expenses') }}" class="text-xs sm:text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentExpenses as $expense)
                    <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start sm:items-center justify-between gap-2 sm:gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                                    <span class="inline-flex self-start px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 whitespace-nowrap">
                                        {{ $expense->category }}
                                    </span>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $expense->description }}</p>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">{{ $expense->expense_date->format('M d, Y') }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="text-sm font-semibold text-red-600 whitespace-nowrap">-¥{{ number_format($expense->amount, 0) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 sm:px-6 py-8 text-center">
                        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-xs sm:text-sm text-gray-500">No expenses yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Expense Breakdown Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Expense Breakdown</h3>
            </div>
            <div class="p-4 sm:p-6">
                @if($expenseByCategory->count() > 0)
                    <div class="flex items-center justify-center">
                        <canvas id="expenseChart" class="max-w-xs max-h-64"></canvas>
                    </div>
                    <div class="mt-6 space-y-2">
                        @foreach($expenseByCategory as $category)
                            @php
                                $percentage = $totalExpenses > 0 ? ($category->total / $totalExpenses) * 100 : 0;
                            @endphp
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $loop->index === 0 ? '#4F46E5' : ($loop->index === 1 ? '#6366F1' : ($loop->index === 2 ? '#818CF8' : '#A5B4FC')) }}"></span>
                                    <span class="text-gray-700">{{ $category->category }}</span>
                                </div>
                                <span class="font-medium text-gray-900">¥{{ number_format($category->total, 0) }} ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No expense data for this month</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if($expenseByCategory->count() > 0)
<script>
    // Pass chart data to the dashboard module
    window.dashboardChartData = {
        labels: @json($expenseByCategory->pluck('category')),
        values: @json($expenseByCategory->pluck('total'))
    };
</script>
@endif
@vite(['resources/js/dashboard.js'])
@endpush
