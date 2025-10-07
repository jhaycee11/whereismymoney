# CSS Architecture Documentation

## 📋 Overview

This project follows a **Tailwind-first** approach with minimal custom CSS. All styling is done using Tailwind utility classes, with custom CSS only for animations and complex transitions that can't be achieved with utilities alone.

---

## 🗂️ File Structure

```
resources/css/
├── app.css          # Main CSS entry point (Tailwind imports + custom CSS imports)
└── modals.css       # Modal animation styles (custom CSS)

resources/views/
└── **/*.blade.php   # Use Tailwind utility classes directly in HTML
```

---

## 📝 CSS Files

### 1. `app.css` - Main Entry Point

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 
                 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 
                 'Noto Color Emoji';
}

/* Import custom component styles */
@import './modals.css';
```

**Purpose:**
- Imports Tailwind CSS v4
- Defines source files for Tailwind scanning
- Sets custom theme variables
- Imports modular CSS files

### 2. `modals.css` - Modal Animations

```css
/**
 * Modal Animation Styles
 * Custom CSS for modal backdrop and content animations
 */

/* Modal backdrop initial state */
.modal-backdrop {
    opacity: 0;
    transition: opacity 200ms ease-out;
}

/* Modal backdrop show state */
.modal-backdrop.modal-show {
    opacity: 1;
}

/* Modal backdrop hide state */
.modal-backdrop.modal-hide {
    opacity: 0;
}

/* Modal content initial state */
.modal-content {
    opacity: 0;
    transform: translateY(-1.25rem) scale(0.95);
    transition: all 200ms ease-out;
}

/* Modal content show state */
.modal-backdrop.modal-show .modal-content {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Modal content hide state */
.modal-backdrop.modal-hide .modal-content {
    opacity: 0;
    transform: translateY(-1.25rem) scale(0.95);
}

/* Enhanced backdrop blur for better visual separation */
@supports (backdrop-filter: blur(4px)) {
    .modal-backdrop {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
}
```

**Purpose:**
- Handles complex modal animations
- Uses `@supports` for progressive enhancement
- Stateful animations (show/hide) that can't be done with Tailwind alone

---

## 🎨 Styling Guidelines

### Tailwind-First Approach

**✅ DO: Use Tailwind utilities directly**

```blade
<!-- Good: Tailwind classes in HTML -->
<div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-lg">
    <h2 class="text-xl font-bold text-gray-900">Title</h2>
    <button class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
        Action
    </button>
</div>
```

**❌ DON'T: Create custom CSS for simple styling**

```css
/* Bad: Custom CSS for basic styling */
.custom-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
}
```

### When to Use Custom CSS

Create custom CSS **ONLY** for:

1. **Complex Animations** - Multi-state transitions
   ```css
   .modal-backdrop.modal-show { opacity: 1; }
   .modal-backdrop.modal-hide { opacity: 0; }
   ```

2. **Progressive Enhancement** - Feature detection
   ```css
   @supports (backdrop-filter: blur(4px)) {
       .modal-backdrop { backdrop-filter: blur(4px); }
   }
   ```

3. **Browser-specific Workarounds** - Vendor prefixes
   ```css
   .element {
       -webkit-backdrop-filter: blur(4px);
       backdrop-filter: blur(4px);
   }
   ```

4. **Dynamic Calculations** - CSS variables, calc()
   ```css
   .element {
       height: calc(100vh - var(--header-height));
   }
   ```

---

## 🚫 Anti-Patterns to Avoid

### ❌ NO Inline Styles in Blade Templates

```blade
<!-- Bad: Inline styles in blade -->
@push('styles')
<style>
    .my-component { color: red; }
</style>
@endpush
```

**Solution:** Use Tailwind classes or create a separate CSS file.

### ❌ NO Arbitrary Values for Common Cases

```blade
<!-- Bad: Using arbitrary values -->
<div class="p-[16px] text-[14px] text-[#374151]">

<!-- Good: Using Tailwind tokens -->
<div class="p-4 text-sm text-gray-700">
```

**Exception:** Use arbitrary values only for truly unique values:
```blade
<!-- Acceptable: Unique brand color -->
<div class="bg-[#FF6B35]">
```

### ❌ NO Duplicate Custom Classes

```css
/* Bad: Creating custom classes for basic layouts */
.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}
```

**Solution:** Use Tailwind utilities:
```blade
<div class="flex items-center justify-center">
```

---

## 📦 Adding New Custom CSS

### Step 1: Create CSS File

```bash
# Create new CSS file for specific component
touch resources/css/charts.css
```

### Step 2: Write Minimal CSS

```css
/**
 * Chart Animations
 * Custom CSS for chart entrance animations
 */

@keyframes chartFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.chart-animate {
    animation: chartFadeIn 300ms ease-out;
}
```

### Step 3: Import in `app.css`

```css
/* Import custom component styles */
@import './modals.css';
@import './charts.css';  /* Add new import */
```

### Step 4: Use in Blade

```blade
<div class="chart-animate">
    <canvas id="myChart"></canvas>
</div>
```

---

## 🎯 Tailwind Configuration

### Using Tailwind v4

This project uses **Tailwind CSS v4** with the new configuration format:

```css
/* In app.css */
@theme {
    --font-sans: 'Instrument Sans', sans-serif;
    /* Add custom theme values here */
}
```

### Custom Color Palette (if needed)

```css
@theme {
    --color-primary: #6366f1;
    --color-secondary: #10b981;
}
```

Use in HTML:
```blade
<div class="bg-primary text-secondary">
```

---

## 🔧 Build Process

### Development

```bash
npm run dev
```

**What happens:**
1. Vite watches for file changes
2. Tailwind scans source files
3. Generates CSS with used utilities
4. Compiles custom CSS imports
5. Hot Module Replacement (HMR) updates browser

### Production

```bash
npm run build
```

**What happens:**
1. Vite optimizes assets
2. Tailwind purges unused CSS
3. Custom CSS is minified
4. Source maps generated
5. Output to `public/build/`

---

## 📊 CSS Organization Best Practices

### File Naming Convention

```
resources/css/
├── app.css          # Main entry (Tailwind + imports)
├── modals.css       # Component-specific
├── charts.css       # Component-specific
├── animations.css   # Shared animations
└── utilities.css    # Custom utility classes (rare)
```

### Class Naming Convention

**For custom CSS:**
- Use kebab-case: `.modal-backdrop`, `.chart-container`
- Prefix component classes: `.modal-`, `.chart-`, `.form-`
- State modifiers: `.modal-show`, `.modal-hide`

**In Blade templates:**
- Use Tailwind utilities: `flex`, `p-4`, `text-lg`
- Combine with custom classes: `modal-backdrop flex items-center`

---

## 🎨 Color System

### Use Tailwind Color Tokens

```blade
<!-- Background colors -->
<div class="bg-gray-50">      <!-- Light backgrounds -->
<div class="bg-white">        <!-- White backgrounds -->
<div class="bg-indigo-600">   <!-- Primary actions -->
<div class="bg-red-600">      <!-- Danger/delete actions -->
<div class="bg-green-600">    <!-- Success states -->

<!-- Text colors -->
<p class="text-gray-900">     <!-- Primary text -->
<p class="text-gray-600">     <!-- Secondary text -->
<p class="text-gray-400">     <!-- Muted text -->
<p class="text-indigo-600">   <!-- Primary links -->
<p class="text-red-600">      <!-- Error text -->

<!-- Opacity variants -->
<div class="bg-gray-900/50">  <!-- 50% opacity -->
<div class="bg-gray-900/75">  <!-- 75% opacity -->
```

---

## 🔍 Debugging CSS

### View Generated CSS

```bash
# See what Tailwind generates
npm run dev -- --debug
```

### Check Used Classes

```bash
# Search for Tailwind class usage
grep -r "bg-indigo-600" resources/views/
```

### Verify Custom CSS

```bash
# Check if custom CSS is imported
cat public/build/assets/*.css | grep "modal-backdrop"
```

---

## ⚡ Performance Optimization

### CSS Bundle Size

- **Development:** ~3MB (all Tailwind utilities)
- **Production:** ~10-20KB (purged, used classes only)

### Optimization Tips

1. **Use Tailwind utilities** - Automatically purged
2. **Minimize custom CSS** - Manually maintained
3. **Avoid `@apply`** - Use utilities directly
4. **Group related classes** - Better readability

---

## 📚 Resources

### Official Documentation
- [Tailwind CSS v4](https://tailwindcss.com/)
- [Vite](https://vitejs.dev/)
- [Laravel Vite](https://laravel.com/docs/vite)

### Tailwind Utilities
- [Spacing](https://tailwindcss.com/docs/padding)
- [Colors](https://tailwindcss.com/docs/text-color)
- [Flexbox](https://tailwindcss.com/docs/flex)
- [Animations](https://tailwindcss.com/docs/animation)

### CSS Best Practices
- [MDN CSS](https://developer.mozilla.org/en-US/docs/Web/CSS)
- [CSS Tricks](https://css-tricks.com/)
- [Web.dev CSS](https://web.dev/learn/css/)

---

## ✅ Checklist for New Styles

Before adding CSS, ask:

- [ ] Can this be done with Tailwind utilities?
- [ ] Is this a one-time unique value? → Use arbitrary value `[...]`
- [ ] Is this reusable complex animation? → Create custom CSS
- [ ] Does this need browser support detection? → Use `@supports`
- [ ] Is this for a specific component? → Create component CSS file
- [ ] Is this a shared pattern? → Consider Tailwind plugin instead

---

## 🎓 Examples

### Modal Implementation

```blade
<!-- Blade: Tailwind utilities + custom animation classes -->
<div class="modal-backdrop hidden fixed inset-0 bg-gray-900/50 overflow-y-auto h-full w-full z-50">
    <div class="modal-content relative top-20 mx-auto p-5 border border-gray-200 w-full max-w-md shadow-2xl rounded-lg bg-white">
        <!-- Modal content -->
    </div>
</div>
```

```css
/* CSS: Only animation states */
.modal-backdrop { opacity: 0; }
.modal-backdrop.modal-show { opacity: 1; }
```

```javascript
// JS: Manages state classes
modal.classList.add('modal-show');
```

### Card Component

```blade
<!-- All Tailwind, no custom CSS needed -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Card Title</h3>
    <p class="text-sm text-gray-600">Card content goes here</p>
</div>
```

---

**Last Updated:** October 7, 2025  
**Architecture:** Tailwind-first with minimal custom CSS  
**Build Tool:** Vite + Tailwind CSS v4

