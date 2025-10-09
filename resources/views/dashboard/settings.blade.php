@extends('layouts.dashboard')

@section('title', 'Settings')

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
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Settings</h2>
            <p class="text-xs sm:text-sm text-gray-600">Manage automatic recurring transactions</p>
        </div>
        <!-- Desktop Add Button -->
        <button onclick="RecurringManager.openAddModal()" class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Recurring Transaction
        </button>
    </div>

    <!-- Mobile Floating Action Button (FAB) -->
    <button onclick="RecurringManager.openAddModal()" 
            class="sm:hidden fixed bottom-6 right-6 z-40 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-full shadow-lg hover:shadow-xl active:shadow-md flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-indigo-300"
            aria-label="Add Recurring Transaction">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
        </svg>
    </button>

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-blue-900 mb-1">Automatic Recurring Transactions</h4>
                <p class="text-xs text-blue-700">
                    Set up automatic expenses (like rent, bills) and income (like salary) that occur on the same day every month. 
                    The system will automatically create these transactions for you.
                </p>
            </div>
        </div>
    </div>

    <!-- Recurring Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category/Source</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day of Month</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recurringTransactions as $recurring)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $recurring->type === 'expense' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    @if($recurring->type === 'expense')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Expense
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Income
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $recurring->type === 'expense' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $recurring->category }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $recurring->type === 'expense' ? 'text-red-600' : 'text-green-600' }}">
                                ¥{{ number_format($recurring->amount, 0) }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Day {{ $recurring->day_of_month }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('recurring.toggle', $recurring) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full transition {{ $recurring->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                        @if($recurring->is_active)
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Active
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Inactive
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="RecurringManager.openEditModal({{ $recurring->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 transition">
                                    Edit
                                </button>
                                <button onclick="RecurringManager.confirmDelete({{ $recurring->id }})" class="text-red-600 hover:text-red-900 transition">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No recurring transactions set up</p>
                                <p class="text-xs text-gray-400">Click "Add Recurring Transaction" to create your first automatic transaction</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($recurringTransactions as $recurring)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $recurring->type === 'expense' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($recurring->type) }}
                                </span>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $recurring->type === 'expense' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $recurring->category }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">
                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Day {{ $recurring->day_of_month }} of each month
                            </p>
                            @if($recurring->notes)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $recurring->notes }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 ml-3">
                            <p class="text-lg font-semibold {{ $recurring->type === 'expense' ? 'text-red-600' : 'text-green-600' }} whitespace-nowrap">
                                ¥{{ number_format($recurring->amount, 0) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                        <form action="{{ route('recurring.toggle', $recurring) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-xs font-medium {{ $recurring->is_active ? 'text-green-700 bg-green-50 hover:bg-green-100' : 'text-gray-600 bg-gray-100 hover:bg-gray-200' }} rounded-md transition">
                                @if($recurring->is_active)
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Active
                                @else
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Inactive
                                @endif
                            </button>
                        </form>
                        <button onclick="RecurringManager.openEditModal({{ $recurring->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                        <button onclick="RecurringManager.confirmDelete({{ $recurring->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No recurring transactions set up</p>
                    <p class="text-xs text-gray-400 mt-1">Click the + button to create your first automatic transaction</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add/Edit Recurring Transaction Modal -->
<div id="recurringModal" class="modal-backdrop hidden fixed inset-0 bg-gray-900/50 overflow-y-auto h-full w-full z-[60] px-4" onclick="RecurringManager.closeModalOnBackdrop(event)">
    <div class="modal-content relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border border-gray-200 w-full max-w-md shadow-2xl rounded-lg bg-white mb-10 sm:mb-0" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 id="modalTitle" class="text-lg sm:text-xl font-semibold text-gray-900">Add Recurring Transaction</h3>
            <button onclick="RecurringManager.closeModal()" class="text-gray-400 hover:text-gray-600 transition p-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="recurringForm" method="POST" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            <input type="hidden" id="recurringId" value="">

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                    Type <span class="text-red-500">*</span>
                </label>
                <select id="type" 
                        name="type" 
                        required
                        onchange="RecurringManager.updateCategoryOptions()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Select Type</option>
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
            </div>

            <!-- Category/Source -->
            <div>
                <label for="modal_category" class="block text-sm font-medium text-gray-700 mb-1">
                    <span id="categoryLabel">Category/Source</span> <span class="text-red-500">*</span>
                </label>
                <select id="modal_category" 
                        name="category" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Select Category/Source</option>
                </select>
            </div>

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

            <!-- Day of Month -->
            <div>
                <label for="day_of_month" class="block text-sm font-medium text-gray-700 mb-1">
                    Day of Month <span class="text-red-500">*</span>
                </label>
                <select id="day_of_month" 
                        name="day_of_month" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Select Day</option>
                    @for($day = 1; $day <= 31; $day++)
                        <option value="{{ $day }}">Day {{ $day }}</option>
                    @endfor
                </select>
                <p class="mt-1 text-xs text-gray-500">The day each month when this transaction should be created automatically</p>
            </div>

            <!-- Notes (Optional) -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Notes <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <textarea id="notes" 
                          name="notes" 
                          rows="2"
                          maxlength="1000"
                          placeholder="e.g., Monthly rent payment, Monthly salary..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-3 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="RecurringManager.closeModal()"
                        class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <span id="submitButtonText">Save</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-backdrop hidden fixed inset-0 bg-gray-900/50 overflow-y-auto h-full w-full z-[60] px-4" onclick="RecurringManager.closeDeleteModal()">
    <div class="modal-content relative top-20 sm:top-32 mx-auto p-4 sm:p-5 border border-gray-200 w-full max-w-sm shadow-2xl rounded-lg bg-white" onclick="event.stopPropagation()">
        <div class="text-center">
            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-3 sm:mt-4">Delete Recurring Transaction</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-2">Are you sure you want to delete this recurring transaction? This will not affect existing transactions.</p>
            
            <form id="deleteForm" method="POST" class="mt-4 sm:mt-6">
                @csrf
                @method('DELETE')
                <div class="flex flex-col-reverse sm:flex-row items-center justify-center gap-2 sm:gap-3">
                    <button type="button" 
                            onclick="RecurringManager.closeDeleteModal()"
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
@vite(['resources/js/settings.js'])
@endpush

