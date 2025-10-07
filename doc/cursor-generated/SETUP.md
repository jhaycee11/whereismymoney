# Quick Setup Guide

## 🚀 Quick Start (5 minutes)

### 1. Install Dependencies
```bash
cd whereismymoney
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database
The project is already configured to use SQLite (no additional setup needed!)

```bash
# Run migrations
php artisan migrate

# Seed with demo data (optional but recommended)
php artisan db:seed --class=ExpenseTrackerSeeder
```

### 4. Build Frontend
```bash
# For development with hot reload
npm run dev

# OR for production build
npm run build
```

### 5. Start Server
```bash
# In a new terminal
php artisan serve
```

### 6. Login
Open `http://localhost:8000` and use these credentials:
- **Email**: demo@example.com
- **Password**: password

---

## 📋 What You'll See

After logging in, you'll have access to:

1. **Dashboard** - Overview with:
   - Total Expenses card
   - Total Income card
   - Net Balance card
   - Recent 10 expenses
   - Interactive pie chart showing expense breakdown by category

2. **Expenses Page** - Full list of all expenses

3. **Income Page** - All income records

4. **History Page** - Combined transaction history

---

## 🎨 Design Features

- **Color Scheme**: Indigo primary color with clean grays
- **Modern UI**: Tailwind CSS 4.0 with custom components
- **Responsive**: Works on mobile, tablet, and desktop
- **Charts**: Interactive Chart.js visualizations
- **Icons**: SVG icons throughout

---

## 🔧 Troubleshooting

### Frontend not compiling?
```bash
npm install
npm run build
```

### Database errors?
```bash
php artisan migrate:fresh --seed
```

### Can't login?
Make sure you ran the seeder:
```bash
php artisan db:seed --class=ExpenseTrackerSeeder
```

Or create a new account at: `http://localhost:8000/register`

---

## 📦 What's Included

✅ Complete authentication system  
✅ Dashboard with real-time metrics  
✅ Expense tracking and categorization  
✅ Income management  
✅ Transaction history  
✅ Chart.js visualizations  
✅ Sample data seeder  
✅ Responsive design  
✅ Modern Tailwind CSS styling  

---

## 🛠 Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Blade + Tailwind CSS 4.0
- **Database**: SQLite (MySQL compatible)
- **Charts**: Chart.js
- **Build**: Vite

---

Enjoy tracking your expenses! 💰
