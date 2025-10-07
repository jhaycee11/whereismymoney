# UI Guide - Visual Overview

## 🎨 Design System

### Color Palette
```
Primary:   Indigo #4F46E5 (Buttons, Active States, Charts)
Success:   Green  #059669 (Income, Positive Values)
Danger:    Red    #DC2626 (Expenses, Negative Values)
Neutral:   Gray   #F9FAFB (Backgrounds)
           Gray   #6B7280 (Text)
           Gray   #E5E7EB (Borders)
```

---

## 📱 Pages Overview

### 1. Login Page (`/login`)

**Layout:**
```
┌─────────────────────────────────────┐
│                                     │
│        ExpenseTracker              │
│     Sign in to your account        │
│                                     │
│  ┌───────────────────────────────┐ │
│  │  Email address                │ │
│  │  [input field]               │ │
│  │                               │ │
│  │  Password                     │ │
│  │  [input field]               │ │
│  │                               │ │
│  │  ☑ Remember me               │ │
│  │                               │ │
│  │  [Sign in - Indigo Button]   │ │
│  │                               │ │
│  │  Don't have an account?       │ │
│  │  Sign up                      │ │
│  │                               │ │
│  │  ┌─────────────────────────┐ │ │
│  │  │ Demo Credentials:       │ │ │
│  │  │ demo@example.com        │ │ │
│  │  │ password                │ │ │
│  │  └─────────────────────────┘ │ │
│  └───────────────────────────────┘ │
└─────────────────────────────────────┘
```

**Features:**
- Clean, centered form
- Demo credentials prominently displayed
- Indigo color scheme
- Validation error display
- Remember me checkbox

---

### 2. Register Page (`/register`)

Similar to login but with additional fields:
- Full Name
- Email
- Password
- Confirm Password
- "Create Account" button
- Link to login page

---

### 3. Dashboard Home (`/dashboard`)

**Layout:**
```
┌───────────┬─────────────────────────────────────────────────────┐
│           │  Dashboard                                          │
│  Sidebar  │  Welcome back! Here's your financial overview      │
│           │                                                     │
│  • Dashbd │  ┌─────────┐  ┌─────────┐  ┌─────────┐           │
│  ○ Expens │  │  📉     │  │  📈     │  │  💰     │           │
│  ○ Income │  │  $X,XXX │  │  $X,XXX │  │  $X,XXX │           │
│  ○ Histor │  │ Expenses│  │ Income  │  │ Balance │           │
│  ○ Logout │  └─────────┘  └─────────┘  └─────────┘           │
│           │                                                     │
│  [User]   │  ┌────────────────────┬──────────────────────┐    │
│  Demo     │  │ Recent Expenses    │ Expense Breakdown    │    │
│  User     │  │ ───────────────    │                      │    │
│           │  │ 🏷 Food: $50      │      ◐ Pie Chart     │    │
│           │  │ 🏷 Transport: $30  │                      │    │
│           │  │ 🏷 Shopping: $100  │   🟦 Food 40%       │    │
│           │  │ ...                │   🟦 Transport 25%   │    │
│           │  │ [View All]         │   🟦 Shopping 35%    │    │
│           │  └────────────────────┴──────────────────────┘    │
└───────────┴─────────────────────────────────────────────────────┘
```

**Components:**

#### Metric Cards (3 across)
```
┌─────────────────────────────┐
│ Total Expenses          [🔻]│
│ $2,450.00                   │
│ This month                  │
└─────────────────────────────┘
```
- Red icon for expenses (down arrow)
- Green icon for income (up arrow)
- Indigo icon for balance (scale)
- Large numbers, small subtitles

#### Recent Expenses List
```
┌─────────────────────────────────────┐
│ Recent Expenses          [View All] │
├─────────────────────────────────────┤
│ [Food] Grocery shopping             │
│ Oct 7, 2025              -$50.00    │
├─────────────────────────────────────┤
│ [Transport] Gas refill              │
│ Oct 6, 2025              -$30.00    │
└─────────────────────────────────────┘
```
- Category badges (indigo)
- Date on left, amount on right
- Hover effect
- Limited to 10 entries

#### Expense Breakdown Chart
```
┌─────────────────────────────────┐
│ Expense Breakdown               │
├─────────────────────────────────┤
│                                 │
│        ╱─────╲                 │
│       │   📊  │                │
│        ╲─────╱                 │
│                                 │
│ 🟦 Food        $980.00 (40%)  │
│ 🟦 Transport   $612.50 (25%)  │
│ 🟦 Shopping    $857.50 (35%)  │
└─────────────────────────────────┘
```
- Interactive Chart.js pie chart
- Color gradient (indigo shades)
- Legend with percentages
- Tooltips on hover

---

### 4. Expenses Page (`/dashboard/expenses`)

**Layout:**
```
┌───────────┬─────────────────────────────────────────────┐
│           │  Expenses                  [+ Add Expense]  │
│  Sidebar  │  Manage and track all your expenses        │
│           │                                             │
│  ○ Dashbd │  ┌─────────────────────────────────────┐  │
│  • Expens │  │ DATE    CATEGORY  DESCRIPTION  AMT  │  │
│  ○ Income │  ├─────────────────────────────────────┤  │
│  ○ Histor │  │ Oct 7   [Food]    Grocery      $50 │  │
│  ○ Logout │  │ Oct 6   [Trans]   Gas          $30 │  │
│           │  │ Oct 5   [Shop]    Clothes     $100 │  │
│           │  │ Oct 4   [Utils]   Electric    $120 │  │
│  [User]   │  │ ...                                 │  │
│           │  └─────────────────────────────────────┘  │
│           │  [< 1 2 3 ... 10 >] Pagination           │
└───────────┴─────────────────────────────────────────────┘
```

**Features:**
- Full-width table
- Category badges
- Date formatting
- Pagination
- Edit/Delete buttons (UI ready)
- Empty state with icon

---

### 5. Income Page (`/dashboard/income`)

Similar to expenses page but:
- Green themed badges (vs indigo)
- Source instead of Category
- "Add Income" button
- Positive amounts in green

---

### 6. History Page (`/dashboard/history`)

**Layout:**
```
┌───────────┬──────────────────────────────────────────────┐
│           │  Transaction History                         │
│  Sidebar  │  View complete history of all transactions  │
│           │                                              │
│           │  ┌──────────────────────────────────────┐   │
│           │  │ DATE  TYPE     CAT       DESC   AMT  │   │
│           │  ├──────────────────────────────────────┤   │
│           │  │ Oct 7 [⬇Exp]  [Food]    Groc  -$50 │   │
│           │  │ Oct 6 [⬆Inc]  [Salary]  Pay  +$3000│   │
│           │  │ Oct 5 [⬇Exp]  [Shop]    Cloth -$100│   │
│           │  │ ...                                   │   │
│           │  └──────────────────────────────────────┘   │
└───────────┴──────────────────────────────────────────────┘
```

**Features:**
- Combined view (expenses + income)
- Type badges (red for expense, green for income)
- Icons (↓ for expense, ↑ for income)
- Chronological sorting
- Color-coded amounts

---

## 🧩 UI Components

### Sidebar
```
┌─────────────────┐
│ ExpenseTracker  │ ← Logo/Brand
├─────────────────┤
│  ┌───┐          │
│  │ DU │ Demo    │ ← User Profile
│  └───┘ User     │
├─────────────────┤
│ 🏠 Dashboard    │ ← Active (indigo bg)
│ 💳 Expenses     │
│ 💰 Income       │
│ 🕐 History      │
├─────────────────┤
│ ↩ Logout        │ ← Bottom
└─────────────────┘
```

**Dimensions:** 256px wide, full height

**Features:**
- Fixed position
- White background
- Border on right
- Icons with labels
- Active state (indigo highlight)

### Metric Cards
```
┌─────────────────────────────┐
│ Total Income            [↑] │ ← Icon (top right)
│                             │
│ $5,250.00                   │ ← Large number
│ This month                  │ ← Subtitle
└─────────────────────────────┘
```

**Styling:**
- White background
- Rounded corners (8px)
- Shadow (subtle)
- Border (light gray)
- Padding (24px)
- Icon in colored circle

### Tables
```
┌─────────────────────────────────────────────┐
│ HEADER     HEADER       HEADER      HEADER  │ ← Gray bg
├─────────────────────────────────────────────┤
│ data       [badge]      description  $XX.XX │ ← Hover bg
│ data       [badge]      description  $XX.XX │
└─────────────────────────────────────────────┘
```

**Features:**
- Striped on hover
- Clean borders
- Badge components
- Right-aligned amounts
- Action buttons (Edit/Delete)

### Badges
```
[Food]        ← Indigo (expenses)
[Salary]      ← Green (income sources)
[⬇ Expense]   ← Red (transaction type)
[⬆ Income]    ← Green (transaction type)
```

**Styling:**
- Rounded full
- Small text
- Colored background
- Padding (4px 8px)

### Buttons

**Primary:**
```
[+ Add Expense]
```
- Indigo background
- White text
- Rounded corners
- Icon + text
- Hover: darker indigo

**Secondary:**
```
[Edit] [Delete]
```
- Text only
- Colored text
- No background
- Hover: underline

---

## 📐 Layout System

### Breakpoints
- **Mobile**: < 768px (single column)
- **Tablet**: 768px - 1024px (2 columns)
- **Desktop**: > 1024px (3 columns, sidebar)

### Grid System
- Cards: `grid-cols-1 md:grid-cols-3`
- Content: `grid-cols-1 lg:grid-cols-2`
- Sidebar: Fixed 256px, content fills remaining

### Spacing
- Section gaps: 24px (`gap-6`)
- Card padding: 24px (`p-6`)
- Element spacing: 16px (`space-y-4`)

---

## 🎯 Interactive Elements

### Hover States
- **Cards**: Subtle shadow increase
- **Table rows**: Light gray background
- **Buttons**: Darker shade
- **Links**: Underline + color change

### Active States
- **Sidebar items**: Indigo background + text
- **Form inputs**: Indigo border/ring

### Empty States
- Large icon (gray)
- Message text
- Helpful subtitle
- Call-to-action button

---

## ♿ Accessibility

- ✅ Semantic HTML (header, nav, main)
- ✅ Proper heading hierarchy
- ✅ Form labels associated with inputs
- ✅ ARIA labels on icons
- ✅ Keyboard navigation support
- ✅ Focus indicators (indigo ring)
- ✅ Color contrast (WCAG AA)

---

## 📱 Mobile Responsiveness

### Mobile View (< 768px)
- Sidebar converts to top navigation
- Single column cards
- Stacked table on small screens
- Touch-friendly button sizes (44px min)

### Tablet View (768px - 1024px)
- Sidebar visible
- 2-column grid for dashboard
- Comfortable spacing

### Desktop View (> 1024px)
- Full sidebar
- 3-column metric cards
- 2-column dashboard content
- Maximum content width

---

## 🎨 Visual Hierarchy

### Typography Scale
```
Page Title:     text-2xl (24px) - Bold
Card Title:     text-lg (18px) - Semibold
Table Header:   text-xs (12px) - Medium, Uppercase
Body Text:      text-sm (14px) - Regular
Subtitle:       text-xs (12px) - Regular
Large Number:   text-3xl (30px) - Bold
```

### Color Hierarchy
1. **Primary actions**: Indigo
2. **Positive values**: Green
3. **Negative values**: Red
4. **Neutral text**: Gray-900 (dark)
5. **Secondary text**: Gray-600 (medium)
6. **Muted text**: Gray-500 (light)

---

## ✨ Animations & Transitions

- **Hover**: 150ms ease-in-out
- **Page load**: Fade in
- **Chart render**: Animated draw
- **Form validation**: Shake on error
- **Modal**: Slide up (if added)

---

## 📊 Chart Styling

### Pie Chart
- **Type**: Doughnut
- **Colors**: Indigo gradient (#4F46E5 → #C7D2FE)
- **Border**: None
- **Tooltips**: White background, shadow
- **Legend**: Below chart, with percentages
- **Responsive**: Maintains aspect ratio

---

This UI guide represents the complete visual design system implemented in the expense tracker dashboard. All components use Tailwind CSS utility classes for consistent, maintainable styling.
