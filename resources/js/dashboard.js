/**
 * Dashboard Module - JavaScript
 * Handles chart visualization and dashboard interactions
 * 
 * @author AI Assistant
 * @version 1.0.0
 */

// ============================================================================
// Constants & Configuration
// ============================================================================

const CONFIG = {
    CHART_COLORS: [
        '#4F46E5', // Indigo-600
        '#6366F1', // Indigo-500
        '#818CF8', // Indigo-400
        '#A5B4FC', // Indigo-300
        '#C7D2FE', // Indigo-200
        '#E0E7FF', // Indigo-100
    ],
    CHART_OPTIONS: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += '¥' + context.parsed.toLocaleString();
                        return label;
                    }
                }
            }
        }
    }
};

const DOM_SELECTORS = {
    EXPENSE_CHART: 'expenseChart',
};

// ============================================================================
// Chart Management
// ============================================================================

/**
 * Chart instance storage
 * @type {Chart|null}
 */
let expenseChartInstance = null;

/**
 * Initialize expense breakdown chart
 * @param {Object} data - Chart data object
 * @param {Array<string>} data.labels - Category labels
 * @param {Array<number>} data.values - Expense values
 */
const initializeExpenseChart = (data) => {
    const canvas = document.getElementById(DOM_SELECTORS.EXPENSE_CHART);
    
    if (!canvas) {
        console.warn('Chart canvas element not found');
        return;
    }

    if (!data || !data.labels || !data.values) {
        console.warn('Invalid chart data provided');
        return;
    }

    if (data.labels.length === 0 || data.values.length === 0) {
        console.info('No chart data available');
        return;
    }

    try {
        const ctx = canvas.getContext('2d');
        
        // Destroy existing chart instance if it exists
        if (expenseChartInstance) {
            expenseChartInstance.destroy();
        }

        expenseChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: CONFIG.CHART_COLORS,
                    borderWidth: 0,
                }]
            },
            options: CONFIG.CHART_OPTIONS
        });

        console.log('Expense chart initialized successfully');
    } catch (error) {
        console.error('Error initializing chart:', error);
    }
};

/**
 * Update existing chart with new data
 * @param {Object} data - New chart data
 */
const updateExpenseChart = (data) => {
    if (!expenseChartInstance) {
        console.warn('Chart not initialized, creating new chart');
        initializeExpenseChart(data);
        return;
    }

    try {
        expenseChartInstance.data.labels = data.labels;
        expenseChartInstance.data.datasets[0].data = data.values;
        expenseChartInstance.update();
        console.log('Chart updated successfully');
    } catch (error) {
        console.error('Error updating chart:', error);
    }
};

/**
 * Destroy chart instance (cleanup)
 */
const destroyChart = () => {
    if (expenseChartInstance) {
        expenseChartInstance.destroy();
        expenseChartInstance = null;
        console.log('Chart destroyed');
    }
};

// ============================================================================
// Utility Functions
// ============================================================================

/**
 * Format currency value
 * @param {number} value - Amount to format
 * @param {string} currency - Currency symbol
 * @returns {string} Formatted currency string
 */
const formatCurrency = (value, currency = '¥') => {
    return `${currency}${Math.abs(value).toLocaleString()}`;
};

/**
 * Calculate percentage
 * @param {number} value - Part value
 * @param {number} total - Total value
 * @param {number} decimals - Decimal places
 * @returns {string} Percentage string
 */
const calculatePercentage = (value, total, decimals = 1) => {
    if (total === 0) return '0.0';
    return ((value / total) * 100).toFixed(decimals);
};

// ============================================================================
// Data Management
// ============================================================================

/**
 * Parse chart data from DOM or window object
 * @returns {Object|null} Chart data or null if not found
 */
const getChartDataFromWindow = () => {
    if (window.dashboardChartData) {
        return window.dashboardChartData;
    }
    return null;
};

// ============================================================================
// Initialization
// ============================================================================

/**
 * Initialize dashboard module
 */
const init = () => {
    console.log('Dashboard module initializing...');

    // Get chart data from window object (set by blade template)
    const chartData = getChartDataFromWindow();
    
    if (chartData) {
        initializeExpenseChart(chartData);
    } else {
        console.info('No chart data found on initialization');
    }

    console.log('Dashboard module initialized');
};

/**
 * Cleanup on page unload
 */
const cleanup = () => {
    destroyChart();
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// Cleanup on page unload
window.addEventListener('beforeunload', cleanup);

// ============================================================================
// Public API - Expose functions to global scope
// ============================================================================

window.DashboardManager = {
    initializeExpenseChart,
    updateExpenseChart,
    destroyChart,
    formatCurrency,
    calculatePercentage,
};

// Export for module usage (if using build tools)
export {
    initializeExpenseChart,
    updateExpenseChart,
    destroyChart,
    formatCurrency,
    calculatePercentage,
};

