# Income List Feature

## Flow

1. **Display Income Records**
   - Fetch the list of incomes from the database.
   - Display them in a **table** format with columns:

     | Date       | Source       | Description | Amount | Notes (optional) | Actions |
     |------------|--------------|------------|--------|-----------------|---------|
   - Paginate results for better performance.

2. **Search and Filter (Hidable)**
   - Add a search bar that can be **shown or hidden**.
   - Allow filtering by:
     - Date range
     - Source
     - Description text
     - Amount range
   - Include a **reset button** to clear filters.
   - Use **debounce** on search input for performance.

3. **Sorting**
   - Enable sorting by columns (Date, Amount, Source).
   - Toggle ascending/descending order.

4. **Add/Edit/Delete**
   - **Add Income:** Modal or form to add new record.
   - **Edit Income:** Inline editing or modal.
   - **Delete Income:** Soft delete preferred; confirmation modal recommended.

5. **Export/Download**
   - Export income list to CSV, Excel, or PDF.
   - Apply active filters in export.

6. **Pagination**
   - Limit records per page (10, 25, 50).
   - Navigation buttons for previous/next pages.

---

## Best Practices

- **Validation:** Ensure amount is numeric and positive, required fields filled.  
- **Security:** Protect actions with authorization checks.  
- **UI/UX:**  
  - Responsive tables for mobile.  
  - Hidable search panel for smaller screens.  
- **Performance:**  
  - Server-side pagination and filtering for large datasets.  
  - Database indexing on frequently filtered columns.  
- **Code Structure:**  
  - Separate concerns: Controller fetches data, View displays.  
  - Reusable components for table, filters, and modals.  
- **Accessibility:**  
  - Table readable with screen readers.  
  - Labels for input fields and buttons.

---

## Suggested Additional Features

- **Notes Column**
  - Optional text field for extra context.  
- **Summary / Totals**
  - Show total income per period (daily, monthly, yearly).  
- **Highlighting**
  - Highlight high amounts or overdue entries.  
- **Custom Filters**
  - Save commonly used filters for quick access.  
- **Notifications**
  - Optional: Notify when a new income record is added.

---

## Example Table Layout

| Date       | Source       | Description       | Amount | Notes       | Actions |
|------------|--------------|-----------------|--------|------------|---------|
| 2025-10-01 | Freelance    | Payment A       | 500    | Urgent     | Edit/Delete |
| 2025-10-02 | Investment   | Dividend        | 200    | Optional   | Edit/Delete |
| 2025-10-03 | Part-time Job| Payment B       | 350    | Follow-up  | Edit/Delete |
