/**
 * Recurring Transaction Manager - JavaScript Module
 * Handles CRUD operations for recurring transactions in settings
 * 
 * @author AI Assistant
 * @version 1.0.0
 */

// ============================================================================
// Constants & Configuration
// ============================================================================

const CONFIG = {
    API_BASE_URL: '/dashboard/settings/recurring',
    ERROR_MESSAGES: {
        FETCH_FAILED: 'Failed to load recurring transaction data. Please try again.',
        NETWORK_ERROR: 'Network error. Please check your connection.',
    }
};

const DOM_SELECTORS = {
    // Modals
    RECURRING_MODAL: 'recurringModal',
    DELETE_MODAL: 'deleteModal',
    
    // Forms
    RECURRING_FORM: 'recurringForm',
    DELETE_FORM: 'deleteForm',
    METHOD_FIELD: 'methodField',
    
    // Modal Elements
    MODAL_TITLE: 'modalTitle',
    SUBMIT_BUTTON_TEXT: 'submitButtonText',
    RECURRING_ID: 'recurringId',
    
    // Form Fields
    TYPE: 'type',
    CATEGORY: 'modal_category',
    AMOUNT: 'amount',
    DAY_OF_MONTH: 'day_of_month',
    NOTES: 'notes',
    CATEGORY_LABEL: 'categoryLabel',
};

const MODAL_CONFIG = {
    ADD: {
        title: 'Add Recurring Transaction',
        submitText: 'Save',
        method: 'POST',
    },
    EDIT: {
        title: 'Edit Recurring Transaction',
        submitText: 'Update',
        method: 'PUT',
    }
};

const CATEGORY_OPTIONS = {
    expense: {
        label: 'Category',
        options: [
            'Food & Dining',
            'Transportation',
            'Shopping',
            'Entertainment',
            'Bills & Utilities',
            'Healthcare',
            'Education',
            'Personal Care',
            'Travel',
            'Housing',
            'Other'
        ]
    },
    income: {
        label: 'Source',
        options: [
            'Salary',
            'Freelance',
            'Investment',
            'Part-time Job',
            'Business',
            'Bonus',
            'Gift',
            'Other'
        ]
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
    
    // Add animation classes and prevent body scroll
    requestAnimationFrame(() => {
        modal.classList.add('modal-show');
        modal.classList.remove('modal-hide');
        
        // Prevent body scroll when modal is open (inside RAF for smooth transition)
        document.body.style.overflow = 'hidden';
        document.body.style.position = 'fixed';
        document.body.style.width = '100%';
    });
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
        
        // Restore body scroll after modal is hidden
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.width = '';
    }, 200); // Match transition duration
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
// Category Management
// ============================================================================

/**
 * Update category dropdown options based on selected type
 */
const updateCategoryOptions = () => {
    const type = getElement(DOM_SELECTORS.TYPE)?.value;
    const categorySelect = getElement(DOM_SELECTORS.CATEGORY);
    const categoryLabel = getElement(DOM_SELECTORS.CATEGORY_LABEL);
    
    if (!categorySelect) return;

    if (type === 'expense' || type === 'income') {
        const config = CATEGORY_OPTIONS[type];
        
        if (categoryLabel) {
            categoryLabel.textContent = config.label;
        }
        
        const options = config.options.map(opt => 
            `<option value="${opt}">${opt}</option>`
        ).join('');
        
        categorySelect.innerHTML = `<option value="">Select ${config.label}</option>${options}`;
    } else {
        if (categoryLabel) {
            categoryLabel.textContent = 'Category/Source';
        }
        categorySelect.innerHTML = '<option value="">First select a type</option>';
    }
};

// ============================================================================
// Modal Management
// ============================================================================

/**
 * Configure modal for add or edit mode
 * @param {Object} config - Modal configuration
 * @param {string} recurringId - Recurring transaction ID (for edit mode)
 */
const configureModal = (config, recurringId = '') => {
    const titleElement = getElement(DOM_SELECTORS.MODAL_TITLE);
    const submitButton = getElement(DOM_SELECTORS.SUBMIT_BUTTON_TEXT);
    const form = getElement(DOM_SELECTORS.RECURRING_FORM);
    const methodField = getElement(DOM_SELECTORS.METHOD_FIELD);
    
    if (titleElement) titleElement.textContent = config.title;
    if (submitButton) submitButton.textContent = config.submitText;
    if (methodField) methodField.value = config.method;
    
    if (form) {
        form.action = recurringId 
            ? `${CONFIG.API_BASE_URL}/${recurringId}`
            : CONFIG.API_BASE_URL;
    }
    
    setFieldValue(DOM_SELECTORS.RECURRING_ID, recurringId);
};

/**
 * Open modal for adding new recurring transaction
 */
const openAddModal = () => {
    configureModal(MODAL_CONFIG.ADD);
    resetForm(DOM_SELECTORS.RECURRING_FORM);
    
    // Clear category options
    const categorySelect = getElement(DOM_SELECTORS.CATEGORY);
    if (categorySelect) {
        categorySelect.innerHTML = '<option value="">First select a type</option>';
    }
    
    showModal(DOM_SELECTORS.RECURRING_MODAL);
};

/**
 * Fetch recurring transaction data from API
 * @param {number} recurringId - Recurring transaction ID
 * @returns {Promise<Object>} Recurring transaction data
 */
const fetchRecurringData = async (recurringId) => {
    const response = await fetch(`${CONFIG.API_BASE_URL}/${recurringId}`, {
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
 * Populate form with recurring transaction data
 * @param {Object} recurring - Recurring transaction data object
 */
const populateForm = (recurring) => {
    // Fill type first
    setFieldValue(DOM_SELECTORS.TYPE, recurring.type);
    
    // Update category options based on type
    updateCategoryOptions();
    
    // Then fill other fields after a brief delay to ensure dropdown is populated
    setTimeout(() => {
        setFieldValue(DOM_SELECTORS.CATEGORY, recurring.category);
        setFieldValue(DOM_SELECTORS.AMOUNT, recurring.amount);
        setFieldValue(DOM_SELECTORS.DAY_OF_MONTH, recurring.day_of_month);
        setFieldValue(DOM_SELECTORS.NOTES, recurring.notes);
    }, 100);
};

/**
 * Open modal for editing recurring transaction
 * @param {number} recurringId - Recurring transaction ID
 */
const openEditModal = async (recurringId) => {
    try {
        const recurring = await fetchRecurringData(recurringId);
        configureModal(MODAL_CONFIG.EDIT, recurringId);
        populateForm(recurring);
        showModal(DOM_SELECTORS.RECURRING_MODAL);
    } catch (error) {
        console.error('Error loading recurring transaction:', error);
        showError(error.message || CONFIG.ERROR_MESSAGES.NETWORK_ERROR);
    }
};

/**
 * Close recurring transaction modal
 */
const closeModal = () => {
    hideModal(DOM_SELECTORS.RECURRING_MODAL);
    resetForm(DOM_SELECTORS.RECURRING_FORM);
};

/**
 * Handle backdrop click to close modal
 * @param {Event} event - Click event
 */
const closeModalOnBackdrop = (event) => {
    if (event.target.id === DOM_SELECTORS.RECURRING_MODAL) {
        closeModal();
    }
};

// ============================================================================
// Delete Functionality
// ============================================================================

/**
 * Open delete confirmation modal
 * @param {number} recurringId - Recurring transaction ID to delete
 */
const confirmDelete = (recurringId) => {
    const deleteForm = getElement(DOM_SELECTORS.DELETE_FORM);
    if (deleteForm) {
        deleteForm.action = `${CONFIG.API_BASE_URL}/${recurringId}`;
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
};

// ============================================================================
// Initialization
// ============================================================================

/**
 * Initialize the recurring transactions module
 */
const init = () => {
    initializeEventListeners();
    console.log('Recurring transactions module initialized');
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

window.RecurringManager = {
    openAddModal,
    openEditModal,
    closeModal,
    closeModalOnBackdrop,
    confirmDelete,
    closeDeleteModal,
    updateCategoryOptions,
};

// Export for module usage (if using build tools)
export {
    openAddModal,
    openEditModal,
    closeModal,
    closeModalOnBackdrop,
    confirmDelete,
    closeDeleteModal,
    updateCategoryOptions,
};
