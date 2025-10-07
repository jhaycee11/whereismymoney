/**
 * Income Management - JavaScript Module
 * Handles CRUD operations for income with modal interactions
 * 
 * @author AI Assistant
 * @version 1.0.0
 */

// ============================================================================
// Constants & Configuration
// ============================================================================

const CONFIG = {
    API_BASE_URL: '/dashboard/income',
    DEFAULT_DATE: new Date().toISOString().split('T')[0],
    ERROR_MESSAGES: {
        FETCH_FAILED: 'Failed to load income data. Please try again.',
        NETWORK_ERROR: 'Network error. Please check your connection.',
    }
};

const DOM_SELECTORS = {
    // Modals
    INCOME_MODAL: 'incomeModal',
    DELETE_MODAL: 'deleteModal',
    
    // Forms
    INCOME_FORM: 'incomeForm',
    DELETE_FORM: 'deleteForm',
    METHOD_FIELD: 'methodField',
    
    // Modal Elements
    MODAL_TITLE: 'modalTitle',
    SUBMIT_BUTTON_TEXT: 'submitButtonText',
    INCOME_ID: 'incomeId',
    
    // Form Fields
    AMOUNT: 'amount',
    SOURCE: 'modal_source',
    DESCRIPTION: 'description',
    INCOME_DATE: 'income_date',
    NOTES: 'notes',
};

const MODAL_CONFIG = {
    ADD: {
        title: 'Add Income',
        submitText: 'Save Income',
        method: 'POST',
    },
    EDIT: {
        title: 'Edit Income',
        submitText: 'Update Income',
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
 * Show modal with smooth animation
 * @param {string} modalId - Modal element ID
 */
const showModal = (modalId) => {
    const modal = getElement(modalId);
    if (!modal) return;
    
    // Remove hidden class to make modal visible
    modal.classList.remove('hidden');
    
    // Force reflow to ensure transition works
    modal.offsetHeight;
    
    // Add animation classes
    requestAnimationFrame(() => {
        modal.classList.add('modal-show');
        modal.classList.remove('modal-hide');
    });
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
};

/**
 * Hide modal with smooth animation
 * @param {string} modalId - Modal element ID
 */
const hideModal = (modalId) => {
    const modal = getElement(modalId);
    if (!modal) return;
    
    // Add hide animation classes
    modal.classList.add('modal-hide');
    modal.classList.remove('modal-show');
    
    // Wait for animation to complete before hiding
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('modal-hide');
    }, 200); // Match transition duration
    
    // Restore body scroll
    document.body.style.overflow = '';
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
 * @param {string} incomeId - Income ID (for edit mode)
 */
const configureModal = (config, incomeId = '') => {
    setFieldValue(DOM_SELECTORS.MODAL_TITLE, config.title);
    
    const titleElement = getElement(DOM_SELECTORS.MODAL_TITLE);
    const submitButton = getElement(DOM_SELECTORS.SUBMIT_BUTTON_TEXT);
    const form = getElement(DOM_SELECTORS.INCOME_FORM);
    const methodField = getElement(DOM_SELECTORS.METHOD_FIELD);
    
    if (titleElement) titleElement.textContent = config.title;
    if (submitButton) submitButton.textContent = config.submitText;
    if (methodField) methodField.value = config.method;
    
    if (form) {
        form.action = incomeId 
            ? `${CONFIG.API_BASE_URL}/${incomeId}`
            : CONFIG.API_BASE_URL;
    }
    
    setFieldValue(DOM_SELECTORS.INCOME_ID, incomeId);
};

/**
 * Open modal for adding new income
 */
const openAddModal = () => {
    configureModal(MODAL_CONFIG.ADD);
    resetForm(DOM_SELECTORS.INCOME_FORM);
    setFieldValue(DOM_SELECTORS.INCOME_DATE, CONFIG.DEFAULT_DATE);
    showModal(DOM_SELECTORS.INCOME_MODAL);
};

/**
 * Fetch income data from API
 * @param {number} incomeId - Income ID
 * @returns {Promise<Object>} Income data
 */
const fetchIncomeData = async (incomeId) => {
    const response = await fetch(`${CONFIG.API_BASE_URL}/${incomeId}`, {
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
 * Populate form with income data
 * @param {Object} income - Income data object
 */
const populateForm = (income) => {
    setFieldValue(DOM_SELECTORS.AMOUNT, income.amount);
    setFieldValue(DOM_SELECTORS.SOURCE, income.source);
    setFieldValue(DOM_SELECTORS.DESCRIPTION, income.description);
    setFieldValue(DOM_SELECTORS.INCOME_DATE, income.income_date);
    setFieldValue(DOM_SELECTORS.NOTES, income.notes);
};

/**
 * Open modal for editing income
 * @param {number} incomeId - Income ID
 */
const openEditModal = async (incomeId) => {
    try {
        const income = await fetchIncomeData(incomeId);
        configureModal(MODAL_CONFIG.EDIT, incomeId);
        populateForm(income);
        showModal(DOM_SELECTORS.INCOME_MODAL);
    } catch (error) {
        console.error('Error loading income:', error);
        showError(error.message || CONFIG.ERROR_MESSAGES.NETWORK_ERROR);
    }
};

/**
 * Close income modal
 */
const closeModal = () => {
    hideModal(DOM_SELECTORS.INCOME_MODAL);
    resetForm(DOM_SELECTORS.INCOME_FORM);
};

/**
 * Handle backdrop click to close modal
 * @param {Event} event - Click event
 */
const closeModalOnBackdrop = (event) => {
    if (event.target.id === DOM_SELECTORS.INCOME_MODAL) {
        closeModal();
    }
};

// ============================================================================
// Delete Functionality
// ============================================================================

/**
 * Open delete confirmation modal
 * @param {number} incomeId - Income ID to delete
 */
const confirmDelete = (incomeId) => {
    const deleteForm = getElement(DOM_SELECTORS.DELETE_FORM);
    if (deleteForm) {
        deleteForm.action = `${CONFIG.API_BASE_URL}/${incomeId}`;
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
 * Initialize the income module
 */
const init = () => {
    initializeEventListeners();
    console.log('Income module initialized');
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

window.IncomeManager = {
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

