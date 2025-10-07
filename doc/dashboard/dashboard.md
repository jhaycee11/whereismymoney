# Laravel Expense Tracker - Project Documentation

A comprehensive expense tracking web application built with Laravel, featuring an intuitive dashboard with real-time financial insights and expense management capabilities.

## Table of Contents
- [Features Overview](#features-overview)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Dashboard Structure](#dashboard-structure)
- [Database Schema](#database-schema)
- [Routes](#routes)
- [Controllers](#controllers)
- [Views](#views)
- [Development Guide](#development-guide)

## Features Overview

### Main Navigation (Sidebar)
- **Dashboard** - Main overview with financial insights
- **Expenses** - Manage and track all expenses
- **Income** - Record and manage income sources
- **History** - View complete transaction history
- **Logout** - Secure user logout

### Dashboard Components

#### Monthly Overview Cards
Three key metric cards displaying:
1. **Total Expenses** - Sum of all expenses for the current month
2. **Total Income** - Sum of all income for the current month
3. **Net Balance** - Computed value (Income - Expenses)

#### Recent Expenses History
- Displays up to 10 most recent expense entries
- Shows date, category, description, and amount
- Quick view for recent spending activity

#### Expense Breakdown Chart
- Interactive circle/pie chart visualization
- Shows expense distribution by category
- Percentage breakdown of spending patterns

## Technology Stack

- **Backend Framework**: Laravel 10.x
- **Frontend**: Blade Templates with Alpine.js
- **CSS Framework**: Tailwind CSS
- **Charts**: Chart.js
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze/Sanctum
- **Icons**: Heroicons or Lucide Icons

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & npm

### Setup Instructions

1. Clone the repository
```bash
git clone https://github.com/yourusername/laravel-expense-tracker.git
cd laravel-expense-tracker
```

2. Install PHP dependencies
```bash
composer install
```

3. Install Node dependencies
```bash
npm install
```

4. Create environment file
```bash
cp .env.example .env
```

5. Configure database in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_tracker
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Generate application key
```bash
php artisan key:generate
```

7. Run migrations and seeders
```bash
php artisan migrate --seed
```

8. Build assets
```bash
npm run dev
```

9. Start the development server
```bash
php artisan serve
```

Access the application at `http://localhost:8000`

## Dashboard Structure

### Layout Overview

```
┌─────────────────────────────────────────────┐
│  Sidebar     │    Main Content              │
│              │                               │
│  Dashboard   │  Monthly Overview             │
│  Expenses    │  ┌────┬────┬────┐            │
│  Income      │  │Exp │Inc │Net │            │
│  History     │  └────┴────┴────┘            │
│  Logout      │                               │
│              │  ┌──────────┬──────────┐     │
│              │  │ Recent   │ Breakdown│     │
│              │  │ Expenses │  Chart   │     │
│              │  └──────────┴──────────┘     │
└─────────────────────────────────────────────┘
```

### Component Breakdown

#### 1. Sidebar Navigation
- Fixed position on the left
- Contains main navigation links
- Active state highlighting
- User profile section at top
- Logout button at bottom

#### 2. Monthly Overview Cards (Top Section)
Three cards in a responsive grid:

**Card 1: Total Expenses**
- Large number displaying total expenses
- Icon: Arrow down (red/danger color)
- Subtitle: "This month"
- Background: Light red/pink

**Card 2: Total Income**
- Large number displaying total income
- Icon: Arrow up (green/success color)
- Subtitle: "This month"
- Background: Light green

**Card 3: Net Balance**
- Large number showing computed balance
- Icon: Wallet or calculator
- Subtitle: "Income - Expenses"
- Background: Light blue
- Dynamic color based on positive/negative balance

#### 3. Recent Expenses History Card
- Table format showing:
  - Date
  - Category (with color badge)
  - Description
  - Amount
- Displays last 10 entries
- "View All" link to expenses page
- Scrollable if needed

#### 4. Expense Breakdown Chart Card
- Circular/Pie chart using Chart.js
- Categories as segments
- Legend showing category names and percentages
- Color-coded for easy identification
- Responsive design