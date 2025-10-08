@extends('layouts.dashboard')

@section('title', 'Expenses')

@section('content')
<div class="space-y-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center justify-between" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-800 hover:text-green-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4" x-data="{ show: true }" x-show="show">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="text-red-800 hover:text-red-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Expenses</h2>
            <p class="text-xs sm:text-sm text-gray-600">Manage and track all your expenses</p>
        </div>
        <button onclick="ExpenseManager.openAddModal()" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition whitespace-nowrap">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Expense
        </button>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ showFilters: true }">
        <!-- Filter Toggle Button -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Search & Filters</h3>
            <button type="button" 
                    @click="showFilters = !showFilters"
                    class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                <svg class="w-4 h-4 ml-1 transition-transform" :class="{'rotate-180': !showFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Filter Form -->
        <div x-show="showFilters" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="p-4">
            <form action="{{ route('dashboard.expenses') }}" method="GET" class="space-y-4">
                <!-- Row 1: Description, Amount, Category -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Description Search -->
                    <div>
                        <label for="description_search" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" 
                               id="description_search" 
                               name="description_search" 
                               value="{{ request('description_search') }}"
                               placeholder="Search description..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    <!-- Amount Search -->
                    <div>
                        <label for="amount_search" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" 
                               id="amount_search" 
                               name="amount_search" 
                               value="{{ request('amount_search') }}"
                               placeholder="Search amount..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="category" 
                                name="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="all">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Row 2: Start Date, End Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ request('start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ request('end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('dashboard.expenses') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $expense->expense_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-gray-900">
                                {{ $expense->description }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600 text-left">
                                ¥{{ number_format($expense->amount, 0) }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-gray-500">
                                @if($expense->notes)
                                    <span class="inline-block max-w-xs truncate" title="{{ $expense->notes }}">
                                        {{ Str::limit($expense->notes, 50) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">—</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="ExpenseManager.openEditModal({{ $expense->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition">
                                    Edit
                                </button>
                                <button onclick="ExpenseManager.confirmDelete({{ $expense->id }})" class="text-red-600 hover:text-red-900 transition">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No expenses found</p>
                                <p class="text-xs text-gray-400">
                                    @if(request()->hasAny(['description_search', 'amount_search', 'category', 'start_date', 'end_date']))
                                        Try adjusting your filters or <a href="{{ route('dashboard.expenses') }}" class="text-indigo-600 hover:text-indigo-700">clear all filters</a>
                                    @else
                                        Click "Add Expense" to create your first entry
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($expenses as $expense)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 whitespace-nowrap">
                                    {{ $expense->category }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $expense->expense_date->format('M d, Y') }}</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                            @if($expense->notes)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $expense->notes }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 ml-3">
                            <p class="text-lg font-semibold text-red-600 whitespace-nowrap">¥{{ number_format($expense->amount, 0) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-100">
                        <button onclick="ExpenseManager.openEditModal({{ $expense->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                        <button onclick="ExpenseManager.confirmDelete({{ $expense->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No expenses found</p>
                    <p class="text-xs text-gray-400 mt-1">
                        @if(request()->hasAny(['description_search', 'amount_search', 'category', 'start_date', 'end_date']))
                            Try adjusting your filters or <a href="{{ route('dashboard.expenses') }}" class="text-indigo-600 hover:text-indigo-700">clear all filters</a>
                        @else
                            Click "Add Expense" to create your first entry
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($expenses->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add/Edit Expense Modal -->
<div id="expenseModal" class="modal-backdrop hidden fixed inset-0 bg-gray-900/50 overflow-y-auto h-full w-full z-50 px-4" onclick="ExpenseManager.closeModalOnBackdrop(event)">
    <div class="modal-content relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border border-gray-200 w-full max-w-md shadow-2xl rounded-lg bg-white mb-10 sm:mb-0" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 id="modalTitle" class="text-lg sm:text-xl font-semibold text-gray-900">Add Expense</h3>
            <button onclick="ExpenseManager.closeModal()" class="text-gray-400 hover:text-gray-600 transition p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="expenseForm" method="POST" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            <input type="hidden" id="expenseId" value="">

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                    Amount <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">¥</span>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           step="1" 
                           min="1"
                           required
                           placeholder="0"
                           class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <!-- Category -->
            <div>
                <label for="modal_category" class="block text-sm font-medium text-gray-700 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="modal_category" 
                        name="category" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Select Category</option>
                    <option value="Food & Dining">Food & Dining</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Shopping">Shopping</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Bills & Utilities">Bills & Utilities</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Education">Education</option>
                    <option value="Personal Care">Personal Care</option>
                    <option value="Travel">Travel</option>
                    <option value="Housing">Housing</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="description" 
                       name="description" 
                       required
                       maxlength="500"
                       placeholder="Enter expense description"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Date -->
            <div>
                <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Date <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="expense_date" 
                       name="expense_date" 
                       required
                       max="{{ date('Y-m-d') }}"
                       value="{{ date('Y-m-d') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Notes (Optional) -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Notes <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <textarea id="notes" 
                          name="notes" 
                          rows="3"
                          maxlength="1000"
                          placeholder="Additional notes or details..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-3 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="ExpenseManager.closeModal()"
                        class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <span id="submitButtonText">Save Expense</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-backdrop hidden fixed inset-0 bg-gray-900/50 overflow-y-auto h-full w-full z-50 px-4" onclick="ExpenseManager.closeDeleteModal()">
    <div class="modal-content relative top-20 sm:top-32 mx-auto p-4 sm:p-5 border border-gray-200 w-full max-w-sm shadow-2xl rounded-lg bg-white" onclick="event.stopPropagation()">
        <div class="text-center">
            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-3 sm:mt-4">Delete Expense</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-2">Are you sure you want to delete this expense? This action cannot be undone.</p>
            
            <form id="deleteForm" method="POST" class="mt-4 sm:mt-6">
                @csrf
                @method('DELETE')
                <div class="flex flex-col-reverse sm:flex-row items-center justify-center gap-2 sm:gap-3">
                    <button type="button" 
                            onclick="ExpenseManager.closeDeleteModal()"
                            class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@vite(['resources/js/expenses.js'])
@endpush
