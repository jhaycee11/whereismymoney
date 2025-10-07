# Where Is My Money - Expense Tracker

A modern, minimalist expense tracking web application built with Laravel 11 and Tailwind CSS 4. Track your expenses, manage income, and visualize your financial data with beautiful charts.

## Features

- 📊 **Dashboard Overview** - Real-time financial insights with monthly summaries
- 💰 **Expense Management** - Track and categorize all your expenses
- 💵 **Income Tracking** - Record and manage income from multiple sources
- 📈 **Visual Analytics** - Interactive pie charts showing expense breakdowns
- 📜 **Transaction History** - Complete history of all financial transactions
- 🎨 **Modern UI** - Clean, simple design using Indigo color scheme with Tailwind CSS

## Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates with Tailwind CSS 4.0
- **Charts**: Chart.js
- **Database**: MySQL/SQLite
- **Build Tool**: Vite

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & npm
- MySQL 8.0 or SQLite (already configured)

## Installation

1. **Navigate to the project directory**
   ```bash
   cd whereismymoney
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Set up environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database with sample data (optional)**
   ```bash
   php artisan db:seed --class=ExpenseTrackerSeeder
   ```
   
   This will create a demo user with sample expenses and income:
   - Email: `demo@example.com`
   - Password: `password`

8. **Build frontend assets**
   ```bash
   npm run build
   ```
   
   For development with hot reload:
   ```bash
   npm run dev
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    
    Open your browser and navigate to: `http://localhost:8000`

## Usage

### Authentication

Before using the dashboard, you need to implement authentication. You can:

1. **Use the seeded demo account**:
   - Email: `demo@example.com`
   - Password: `password`

2. **Install Laravel Breeze** (recommended):
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install blade
   npm install && npm run build
   php artisan migrate
   ```

### Dashboard Features

1. **Dashboard** - View monthly overview with:
   - Total Expenses
   - Total Income
   - Net Balance
   - Recent 10 expenses
   - Expense breakdown by category (pie chart)

2. **Expenses** - Manage all your expenses with pagination

3. **Income** - Track income from different sources

4. **History** - View combined transaction history

## Database Schema

### Expenses Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `category` - Expense category
- `description` - Expense description
- `amount` - Amount (decimal)
- `expense_date` - Date of expense
- `timestamps` - Created/updated timestamps

### Incomes Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `source` - Income source
- `description` - Income description (optional)
- `amount` - Amount (decimal)
- `income_date` - Date of income
- `timestamps` - Created/updated timestamps

## Color Scheme

The application uses a simple, modern color palette:
- **Primary**: Indigo (#4F46E5)
- **Success/Income**: Green (#059669)
- **Danger/Expense**: Red (#DC2626)
- **Neutral**: Gray shades for backgrounds and text

## Development

### Running in Development Mode

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (hot reload)
npm run dev
```

### Building for Production

```bash
npm run build
```

## Docker Support

The project includes Docker configuration:

```bash
# Build and start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed --class=ExpenseTrackerSeeder
```

## Contributing

Feel free to submit issues and enhancement requests!

## License

This project is open-sourced software licensed under the MIT license.

## Screenshots

### Dashboard
Modern dashboard with metric cards showing total expenses, income, and net balance. Includes recent expenses list and interactive pie chart.

### Expense Management
Clean table view of all expenses with filtering and pagination.

### Income Tracking
Manage all income sources with detailed records.

### Transaction History
Combined view of all financial transactions (income and expenses).

---

Built with ❤️ using Laravel and Tailwind CSS