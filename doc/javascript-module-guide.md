# JavaScript Module Development Guide

## Quick Start: Adding a New JavaScript Module

### Step 1: Create the Module File

Create a new file in `resources/js/`:

```javascript
/**
 * [Module Name] - JavaScript Module
 * [Brief description of what this module does]
 * 
 * @author [Your Name]
 * @version 1.0.0
 */

// ============================================================================
// Constants & Configuration
// ============================================================================

const CONFIG = {
    API_BASE_URL: '/api/endpoint',
    // Add your configuration here
};

const DOM_SELECTORS = {
    // Add DOM element IDs here
    MAIN_ELEMENT: 'mainElement',
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

// ============================================================================
// Core Functionality
// ============================================================================

// Add your main functions here

// ============================================================================
// Initialization
// ============================================================================

/**
 * Initialize the module
 */
const init = () => {
    console.log('[Module Name] initialized');
    // Your initialization code
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// ============================================================================
// Public API - Expose functions to global scope
// ============================================================================

window.YourModuleName = {
    // Expose public methods here
};

// Export for module usage (if using build tools)
export {
    // Export functions here
};
```

### Step 2: Register Module in Vite Config

Edit `vite.config.js`:

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/expenses.js',
                'resources/js/dashboard.js',
                'resources/js/your-new-module.js', // Add this line
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

### Step 3: Use in Blade Template

At the bottom of your blade file:

#### Option A: Event-Driven Module (with user interactions)

```blade
@endsection

@push('scripts')
@vite(['resources/js/your-module.js'])
@endpush
```

#### Option B: Data-Driven Module (with backend data)

```blade
@endsection

@push('scripts')
@if($someData->count() > 0)
<script>
    // Pass data to JavaScript module
    window.yourModuleData = {
        items: @json($someData),
        config: @json($config)
    };
</script>
@endif
@vite(['resources/js/your-module.js'])
@endpush
```

### Step 4: Build Assets

```bash
npm run dev   # For development with hot reload
npm run build # For production
```

---

## Architecture Patterns

### Pattern 1: Event-Driven (User Interactions)

**Use When:**
- Handling user interactions (clicks, form submissions)
- Modal dialogs
- CRUD operations
- Interactive UI components

**Example:** `expenses.js`

**Key Features:**
```javascript
// 1. Public API via namespace
window.ModuleName = {
    openModal,
    closeModal,
    saveData,
};

// 2. Call from HTML
<button onclick="ModuleName.openModal()">Open</button>

// 3. Event listeners
document.addEventListener('keydown', handleKeypress);

// 4. Async API calls
const fetchData = async (id) => {
    const response = await fetch(`/api/endpoint/${id}`);
    return response.json();
};
```

### Pattern 2: Data-Driven (Visualization)

**Use When:**
- Displaying charts/graphs
- Data visualization
- Read-only displays
- No user interaction required

**Example:** `dashboard.js`

**Key Features:**
```javascript
// 1. Get data from window object
const data = window.moduleData;

// 2. Initialize with data
const initChart = (data) => {
    // Chart initialization
};

// 3. Auto-initialize
const init = () => {
    const data = getDataFromWindow();
    if (data) initChart(data);
};

// 4. Cleanup
window.addEventListener('beforeunload', cleanup);
```

---

## Best Practices Checklist

### Code Organization
- [ ] Clear section comments (Constants, Utility, Core, Init, Public API)
- [ ] Functions organized logically
- [ ] Constants at the top
- [ ] Public API at the bottom

### Documentation
- [ ] File header with description
- [ ] JSDoc comments for all functions
- [ ] Parameter types documented
- [ ] Return types documented

### Code Quality
- [ ] No magic strings (use constants)
- [ ] Single responsibility per function
- [ ] DRY principle applied
- [ ] Error handling with try-catch
- [ ] Safe DOM manipulation (null checks)

### Modern JavaScript
- [ ] Use `const`/`let` (not `var`)
- [ ] Arrow functions where appropriate
- [ ] Async/await (not callbacks)
- [ ] Template literals for strings
- [ ] Optional chaining (`?.`)
- [ ] Destructuring where useful

### Performance
- [ ] Deferred script loading
- [ ] Minimal DOM queries (cache elements)
- [ ] Cleanup on page unload
- [ ] Debouncing for frequent events

### Security
- [ ] No inline event handlers if possible
- [ ] Sanitize user input
- [ ] Use CSRF tokens for requests
- [ ] No sensitive data in console.log

---

## Common Patterns

### Safe DOM Element Access

```javascript
const getElement = (id) => document.getElementById(id);

const element = getElement('myId');
if (element) {
    element.textContent = 'Hello';
}

// Or with optional chaining
getElement('myId')?.classList.add('active');
```

### Modal Management

```javascript
const showModal = (modalId) => {
    getElement(modalId)?.classList.remove('hidden');
};

const hideModal = (modalId) => {
    getElement(modalId)?.classList.add('hidden');
};

// Close on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hideModal('myModal');
});

// Close on backdrop click
modal.addEventListener('click', (e) => {
    if (e.target === modal) hideModal('myModal');
});
```

### API Calls with Error Handling

```javascript
const fetchData = async (id) => {
    try {
        const response = await fetch(`/api/items/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        showError(error.message);
        throw error;
    }
};
```

### Form Handling

```javascript
const resetForm = (formId) => {
    getElement(formId)?.reset();
};

const setFormValues = (data) => {
    Object.entries(data).forEach(([key, value]) => {
        const field = getElement(key);
        if (field) field.value = value || '';
    });
};

const getFormData = (formId) => {
    const form = getElement(formId);
    if (!form) return null;
    
    return Object.fromEntries(new FormData(form));
};
```

---

## Testing Your Module

### Manual Testing Checklist

1. **Initialization**
   - [ ] Module loads without errors
   - [ ] Console shows initialization message
   - [ ] DOM elements are found

2. **Functionality**
   - [ ] All functions work as expected
   - [ ] Error messages display correctly
   - [ ] Data updates properly

3. **Edge Cases**
   - [ ] Empty data handling
   - [ ] Network errors
   - [ ] Missing DOM elements
   - [ ] Rapid user interactions

4. **Browser Compatibility**
   - [ ] Chrome
   - [ ] Firefox
   - [ ] Safari
   - [ ] Edge

### Unit Testing (Future)

```javascript
import { yourFunction } from './your-module.js';

describe('YourModule', () => {
    test('should initialize correctly', () => {
        expect(yourFunction()).toBeDefined();
    });
});
```

---

## Debugging Tips

### Enable Verbose Logging

```javascript
const DEBUG = true;

const log = (...args) => {
    if (DEBUG) console.log('[ModuleName]', ...args);
};

// Usage
log('User clicked button', { userId: 123 });
```

### Common Issues

**Issue:** Function not found
```javascript
// ❌ Wrong: Function not exposed
const myFunction = () => { };

// ✅ Correct: Expose via namespace
window.ModuleName = {
    myFunction,
};
```

**Issue:** DOM element not found
```javascript
// ❌ Wrong: Script runs before DOM ready
const element = document.getElementById('myId');

// ✅ Correct: Wait for DOM
document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('myId');
});
```

**Issue:** Chart not rendering
```javascript
// ❌ Wrong: No data validation
new Chart(ctx, { data: chartData });

// ✅ Correct: Validate data first
if (chartData && chartData.labels && chartData.labels.length > 0) {
    new Chart(ctx, { data: chartData });
}
```

---

## Examples from This Project

### Study These Modules

1. **`expenses.js`** - Event-driven CRUD operations
   - Modal management
   - Form handling
   - API interactions
   - Complex state management

2. **`dashboard.js`** - Data-driven visualization
   - Chart initialization
   - Data validation
   - Memory management
   - Simple, focused purpose

### Module Complexity Guide

| Complexity | Lines | Functions | Example Use Case |
|------------|-------|-----------|------------------|
| Simple | 50-100 | 3-5 | Toggle visibility, format data |
| Medium | 100-200 | 5-10 | Form validation, simple charts |
| Complex | 200-400 | 10-20 | CRUD operations, modal management |

---

## Maintenance

### Updating Existing Modules

1. Read the module documentation
2. Add new functions in appropriate sections
3. Update JSDoc comments
4. Test thoroughly
5. Update relevant docs

### Code Review Checklist

- [ ] Follows established patterns
- [ ] Has JSDoc comments
- [ ] No console.log in production code
- [ ] Error handling present
- [ ] No duplicate code
- [ ] Performance considerations
- [ ] Security best practices

---

## Resources

### Internal Docs
- `JS_BEST_PRACTICES.md` - Detailed best practices
- `resources/js/expenses.js` - Event-driven example
- `resources/js/dashboard.js` - Data-driven example

### External Resources
- [MDN JavaScript Guide](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide)
- [JavaScript.info](https://javascript.info/)
- [Chart.js Documentation](https://www.chartjs.org/)
- [Vite Documentation](https://vitejs.dev/)

---

**Last Updated:** October 7, 2025  
**Maintainer:** Development Team

