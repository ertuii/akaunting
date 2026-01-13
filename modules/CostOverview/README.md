# Cost Overview Module for Akaunting

A comprehensive module for creating, managing, and tracking cost overviews with budget visualization.

## Features

- **Cost Overview Management**: Create and manage cost overviews similar to invoices but without tax/accounting impact
- **Status Workflow**: Draft → Sent → Approved → Converted
- **Budget Tracking**: Set budgets and visualize utilization with color-coded progress bars
- **Email Integration**: Send cost overviews to customers via email with customizable templates
- **PDF Export**: Generate professional PDF documents
- **Customer Portal**: Dedicated customer portal view for cost overviews
- **Invoice Conversion**: Convert approved cost overviews to invoice drafts with one click
- **Multi-language Support**: English and German translations included

## Installation

1. Copy the `CostOverview` folder to your Akaunting `modules` directory
2. Run migrations: `php artisan migrate`
3. Clear cache: `php artisan cache:clear`
4. Access the module from the Sales menu

## Usage

### Creating a Cost Overview

1. Navigate to **Sales > Cost Overviews**
2. Click **New Cost Overview**
3. Fill in customer details, items, and optional budget
4. Save as draft or send immediately

### Budget Visualization

The module provides visual budget tracking with three color-coded levels:
- **Green** (0-50%): Budget is in good shape
- **Yellow** (50-80%): Warning - budget consumption high
- **Red** (80%+): Critical - budget nearly exhausted

### Converting to Invoice

Once a cost overview is approved:
1. Open the cost overview
2. Click **Convert to Invoice**
3. A draft invoice will be created with all items and details
4. The cost overview status changes to "Converted"

### Customer Portal

Customers can:
- View their cost overviews
- Download PDF versions
- Track budget utilization
- See status updates

## Configuration

Edit `modules/CostOverview/Config/config.php` to customize:
- Budget threshold colors
- Default status values

## Email Templates

The module includes email templates that can be customized:
- `cost_overview_new_customer`: Email sent to customers with new cost overview

Edit templates in **Settings > Email Templates**.

## Requirements

- Akaunting 3.1 or higher
- PHP 8.1 or higher
- Laravel 10.x

## Support

For issues and feature requests, please contact your system administrator.

## License

Proprietary - All rights reserved
