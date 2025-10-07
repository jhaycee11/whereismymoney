/**
 * Expense Management - JavaScript Module
 * Handles CRUD operations for expenses with modal interactions
 * 
 * @author AI Assistant
 * @version 1.0.0
 */

// ============================================================================
// Constants & Configuration
// ============================================================================

const CONFIG = {
    API_BASE_URL: '/dashboard/expenses',
    DEFAULT_DATE: new Date().toISOString().split('T')[0],
    ERROR_MESSAGES: {
        FETCH_FAILED: 'Failed to load expense data. Please try again.',
        NETWORK_ERROR: 'Network error. Please check your connection.',
    }
};

const DOM_SELECTORS = {
    // Modals
    EXPENSE_MODAL: 'expenseModal',
    DELETE_MODAL: 'deleteModal',
    
    // Forms
    EXPENSE_FORM: 'expenseForm',
    DELETE_FORM: 'deleteForm',
    METHOD_FIELD: 'methodField',
    
    // Modal Elements
    MODAL_TITLE: 'modalTitle',
    SUBMIT_BUTTON_TEXT: 'submitButtonText',
    EXPENSE_ID: 'expenseId',
    
    // Form Fields
    AMOUNT: 'amount',
    CATEGORY: 'modal_category',
    DESCRIPTION: 'description',
    EXPENSE_DATE: 'expense_date',
    NOTES: 'notes',
};

const MODAL_CONFIG = {
    ADD: {
        title: 'Add Expense',
        submitText: 'Save Expense',
        method: 'POST',
    },
    EDIT: {
        title: 'Edit Expense',
        submitText: 'Update Expense',
        method: 'PUT',
    }
};

// ============================================================================
// Utility Functions
// ============================================================================

/**
 * Get DOM element by ID safely
 * @param {string} id - Element ID
 * @returns {HTMLElement|null}
 */
const getElement = (id) => document.getElementById(id);

/**
 * Show modal by removing hidden class
 * @param {string} modalId - Modal element ID
 */
const showModal = (modalId) => {
    const modal = getElement(modalId);
    if (modal) modal.classList.remove('hidden');
};

/**
 * Hide modal by adding hidden class
 * @param {string} modalId - Modal element ID
 */
const hideModal = (modalId) => {
    const modal = getElement(modalId);
    if (modal) modal.classList.add('hidden');
};

/**
 * Reset form to initial state
 * @param {string} formId - Form element ID
 */
const resetForm = (formId) => {
    const form = getElement(formId);
    if (form) form.reset();
};

/**
 * Set form field value safely
 * @param {string} fieldId - Field element ID
 * @param {string|number} value - Value to set
 */
const setFieldValue = (fieldId, value) => {
    const field = getElement(fieldId);
    if (field) field.value = value || '';
};

/**
 * Display error message to user
 * @param {string} message - Error message
 */
const showError = (message) => {
    alert(message);
    console.error(message);
};

// ============================================================================
// Modal Management
// ============================================================================

/**
 * Configure modal for add or edit mode
 * @param {Object} config - Modal configuration
 * @param {string} expenseId - Expense ID (for edit mode)
 */
const configureModal = (config, expenseId = '') => {
    setFieldValue(DOM_SELECTORS.MODAL_TITLE, config.title);
    
    const titleElement = getElement(DOM_SELECTORS.MODAL_TITLE);
    const submitButton = getElement(DOM_SELECTORS.SUBMIT_BUTTON_TEXT);
    const form = getElement(DOM_SELECTORS.EXPENSE_FORM);
    const methodField = getElement(DOM_SELECTORS.METHOD_FIELD);
    
    if (titleElement) titleElement.textContent = config.title;
    if (submitButton) submitButton.textContent = config.submitText;
    if (methodField) methodField.value = config.method;
    
    if (form) {
        form.action = expenseId 
            ? `${CONFIG.API_BASE_URL}/${expenseId}`
            : CONFIG.API_BASE_URL;
    }
    
    setFieldValue(DOM_SELECTORS.EXPENSE_ID, expenseId);
};

/**
 * Open modal for adding new expense
 */
const openAddModal = () => {
    configureModal(MODAL_CONFIG.ADD);
    resetForm(DOM_SELECTORS.EXPENSE_FORM);
    setFieldValue(DOM_SELECTORS.EXPENSE_DATE, CONFIG.DEFAULT_DATE);
    showModal(DOM_SELECTORS.EXPENSE_MODAL);
};

/**
 * Fetch expense data from API
 * @param {number} expenseId - Expense ID
 * @returns {Promise<Object>} Expense data
 */
const fetchExpenseData = async (expenseId) => {
    const response = await fetch(`${CONFIG.API_BASE_URL}/${expenseId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    });
    
    if (!response.ok) {
        throw new Error(CONFIG.ERROR_MESSAGES.FETCH_FAILED);
    }
    
    return response.json();
};

/**
 * Populate form with expense data
 * @param {Object} expense - Expense data object
 */
const populateForm = (expense) => {
    setFieldValue(DOM_SELECTORS.AMOUNT, expense.amount);
    setFieldValue(DOM_SELECTORS.CATEGORY, expense.category);
    setFieldValue(DOM_SELECTORS.DESCRIPTION, expense.description);
    setFieldValue(DOM_SELECTORS.EXPENSE_DATE, expense.expense_date);
    setFieldValue(DOM_SELECTORS.NOTES, expense.notes);
};

/**
 * Open modal for editing expense
 * @param {number} expenseId - Expense ID
 */
const openEditModal = async (expenseId) => {
    try {
        const expense = await fetchExpenseData(expenseId);
        configureModal(MODAL_CONFIG.EDIT, expenseId);
        populateForm(expense);
        showModal(DOM_SELECTORS.EXPENSE_MODAL);
    } catch (error) {
        console.error('Error loading expense:', error);
        showError(error.message || CONFIG.ERROR_MESSAGES.NETWORK_ERROR);
    }
};

/**
 * Close expense modal
 */
const closeModal = () => {
    hideModal(DOM_SELECTORS.EXPENSE_MODAL);
    resetForm(DOM_SELECTORS.EXPENSE_FORM);
};

/**
 * Handle backdrop click to close modal
 * @param {Event} event - Click event
 */
const closeModalOnBackdrop = (event) => {
    if (event.target.id === DOM_SELECTORS.EXPENSE_MODAL) {
        closeModal();
    }
};

// ============================================================================
// Delete Functionality
// ============================================================================

/**
 * Open delete confirmation modal
 * @param {number} expenseId - Expense ID to delete
 */
const confirmDelete = (expenseId) => {
    const deleteForm = getElement(DOM_SELECTORS.DELETE_FORM);
    if (deleteForm) {
        deleteForm.action = `${CONFIG.API_BASE_URL}/${expenseId}`;
    }
    showModal(DOM_SELECTORS.DELETE_MODAL);
};

/**
 * Close delete confirmation modal
 */
const closeDeleteModal = () => {
    hideModal(DOM_SELECTORS.DELETE_MODAL);
};

// ============================================================================
// Event Handlers
// ============================================================================

/**
 * Handle keyboard shortcuts
 * @param {KeyboardEvent} event - Keyboard event
 */
const handleKeyboardShortcuts = (event) => {
    if (event.key === 'Escape') {
        closeModal();
        closeDeleteModal();
    }
};

/**
 * Initialize event listeners
 */
const initializeEventListeners = () => {
    // Keyboard shortcuts
    document.addEventListener('keydown', handleKeyboardShortcuts);
    
    // Alpine.js initialization (if needed)
    document.addEventListener('alpine:init', () => {
        // Alpine.js is already handling flash messages in the template
    });
};

// ============================================================================
// Initialization
// ============================================================================

/**
 * Initialize the expenses module
 */
const init = () => {
    initializeEventListeners();
    console.log('Expenses module initialized');
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// ============================================================================
// Public API - Expose functions to global scope for inline onclick handlers
// ============================================================================

window.ExpenseManager = {
    openAddModal,
    openEditModal,
    closeModal,
    closeModalOnBackdrop,
    confirmDelete,
    closeDeleteModal,
};

// Export for module usage (if using build tools)
export {
    openAddModal,
    openEditModal,
    closeModal,
    closeModalOnBackdrop,
    confirmDelete,
    closeDeleteModal,
};

