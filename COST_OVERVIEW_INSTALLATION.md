# Cost Overview Module - Installation & Setup Guide

## Quick Start

The Cost Overview module is now installed in your Akaunting instance. Follow these steps to activate it:

### 1. Run Database Migrations

```bash
cd /path/to/akaunting
php artisan migrate
```

This will add the `budget` field to the `documents` table.

### 2. Clear Application Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. Seed Email Templates (Optional)

```bash
php artisan db:seed --class=Modules\\CostOverview\\Database\\Seeders\\EmailTemplateSeeder
```

Or run it manually:
1. Go to Settings > Email Templates
2. Create a new template with:
   - Alias: `cost_overview_new_customer`
   - Class: `Modules\CostOverview\Notifications\CostOverviewNotification`
   - Subject: "New Cost Overview: {cost_overview_number}"
   - Body: "Dear {customer_name}, we have prepared a new cost overview..."

### 4. Access the Module

#### Admin Interface
Navigate to: **Sales > Cost Overviews**

#### Customer Portal
Customers will see: **Portal > Cost Overviews**

## First Steps

### Create Your First Cost Overview

1. Go to **Sales > Cost Overviews**
2. Click **"New Cost Overview"**
3. Fill in:
   - Customer (required)
   - Document Number (auto-generated or custom)
   - Issue Date
   - Due Date
   - Currency
   - **Budget** (optional - for visualization)
   - Category
4. Add items:
   - Item name
   - Description
   - Quantity
   - Price
5. Add optional notes and footer
6. Click **"Save"** to create as draft

### Understanding Budget Visualization

The budget feature shows how much of the allocated budget has been consumed:

- **Set Budget**: Enter the maximum budget (e.g., €10,000)
- **Add Items**: As you add items, the total increases
- **Watch Progress**: The progress bar changes color:
  - 🟢 **Green (0-50%)**: Under control
  - 🟡 **Yellow (50-80%)**: Needs attention
  - 🔴 **Red (80%+)**: Critical

### Status Workflow

Cost overviews move through these statuses:

1. **Draft**: Being created/edited
   - Action: Send to customer
   
2. **Sent**: Delivered to customer
   - Action: Mark as approved
   
3. **Approved**: Customer confirmed
   - Action: Convert to invoice
   
4. **Converted**: Turned into an invoice
   - Final state, no further actions

### Sending to Customer

To send a cost overview by email:

1. Open the cost overview
2. Click **"Send Cost Overview"**
3. Email is sent using the template
4. PDF is attached automatically
5. Status changes to "Sent"

### Converting to Invoice

When a customer approves a cost overview:

1. Mark it as **"Approved"**
2. Click **"Convert to Invoice"**
3. A draft invoice is created with:
   - All items copied
   - Same customer details
   - Same amounts
4. Cost overview status → "Converted"
5. You're redirected to the new invoice

### Customer Portal Access

Customers can:
- View their cost overviews
- See budget utilization
- Download PDF
- Print cost overviews
- Track status changes

They **cannot**:
- Edit cost overviews
- Delete cost overviews
- Convert to invoices (admin only)

## Configuration

### Customizing Budget Thresholds

Edit `modules/CostOverview/Config/config.php`:

```php
'budget_thresholds' => [
    'green' => 50,  // Under 50%
    'yellow' => 80, // 50-80%
    'red' => 100,   // Over 80%
],
```

### Customizing Email Templates

1. Go to **Settings > Email Templates**
2. Find **"New Cost Overview - Customer"**
3. Edit subject and body
4. Available placeholders:
   - `{cost_overview_number}`
   - `{customer_name}`
   - `{amount}`
   - `{budget}`

### Customizing Status Labels

The module defines these statuses in translations:
- Edit `modules/CostOverview/Resources/lang/en-GB/general.php`
- Edit `modules/CostOverview/Resources/lang/de-DE/general.php`

## Troubleshooting

### Module not appearing in menu

Run:
```bash
php artisan cache:clear
```

Refresh your browser cache (Ctrl+F5).

### Migration errors

If migration fails, check:
- Database connection is working
- User has ALTER TABLE permissions
- No existing `budget` column in documents table

### Email not sending

Check:
1. Email template exists (Settings > Email Templates)
2. Customer has email address
3. Mail configuration is correct (Settings > Email)
4. SMTP credentials are valid

### PDF not generating

Ensure:
- dompdf is installed: `composer show barryvdh/laravel-dompdf`
- Storage directory is writable
- No special characters in document names

### Budget not showing

Budget visualization only appears when:
- A budget value is entered (> 0)
- The cost overview has items with totals

## Advanced Usage

### Bulk Operations

From the index page, you can:
- Select multiple cost overviews
- Delete in bulk
- Export selected items

### Filtering and Sorting

List view supports:
- Sort by: Number, Customer, Status, Amount, Date
- Filter by: Status, Customer, Date range
- Search by: Document number, Customer name

### Reports

Cost overviews appear in standard reports as document type "cost-overview".

### API Access

Cost overviews use the same API endpoints as documents:
- Endpoint: `/api/v1/documents`
- Filter: `type=cost-overview`

## Support

For issues or questions:
1. Check the README.md in module directory
2. Review the Akaunting documentation
3. Contact your system administrator

## Module Information

- **Version**: 1.0.0
- **Category**: Sales
- **Type**: Document Management
- **Requirements**: 
  - Akaunting 3.1+
  - PHP 8.1+
  - Laravel 10.x
- **Languages**: English, German

## Uninstallation

If you need to remove the module:

1. Delete cost overview data:
   ```sql
   DELETE FROM documents WHERE type = 'cost-overview';
   ```

2. Remove budget column (optional):
   ```sql
   ALTER TABLE documents DROP COLUMN budget;
   ```

3. Delete module directory:
   ```bash
   rm -rf modules/CostOverview
   ```

4. Clear cache:
   ```bash
   php artisan cache:clear
   ```

---

**Enjoy using the Cost Overview module!** 🎉
