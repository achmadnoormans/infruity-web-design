# Laravel POS System - Agent Guide

## Build/Test/Development Commands
- `php artisan serve` - Start development server
- `php artisan test` - Run all tests
- `php artisan test --filter TestClassName` - Run specific test class
- `./vendor/bin/phpunit tests/Unit/ExampleTest.php` - Run single test file
- `npm run dev` - Build assets for development
- `npm run watch` - Watch and rebuild assets
- `npm run production` - Build assets for production
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh --seed` - Reset database with seed data

## Architecture
- **Laravel 10** framework with modular structure using `nwidart/laravel-modules`
- **Modules**: Chat, Crm, Master, Pos, Transaction (in `/Modules/`)
- **Database**: MySQL (default), SQLite for testing
- **Frontend**: Laravel Mix with Bootstrap 4, jQuery, Livewire 3.6
- **Template**: Metronic Admin Dashboard Template
- **Key packages**: DomPDF, Excel export, Barcode/QR generation, DataTables, Captcha

## Template & UI Framework
- **Metronic Template**: Premium admin dashboard template integrated into the Laravel application
- **UI Components**: Metronic provides pre-built components, layouts, and styling
- **Assets Location**: Metronic assets are typically located in `public/assets/` or integrated via Laravel Mix
- **Layout Structure**: Uses Metronic's layout system for consistent admin interface
- **Components**: Leverage Metronic's cards, tables, forms, modals, and other UI elements
- **Responsive Design**: Built-in responsive design following Metronic's grid system
- **Theme Customization**: Metronic allows theme customization through SCSS variables and configuration

## Code Style & Conventions
- **PSR-4 autoloading**: `App\` namespace for main app, `Modules\` for modules
- **Helpers**: Custom helpers in `app/Helpers/` (DateHelper.php, Random.php)
- **Migrations**: Use descriptive names, follow Laravel conventions
- **Controllers**: Use resource controllers, proper HTTP methods
- **Models**: Use Eloquent ORM, define relationships clearly
- **Views**: Blade templates, use components for reusability
- **Testing**: PHPUnit with Feature/Unit test separation

## Metronic Template Guidelines
- **Layout Usage**: Extend Metronic base layouts (`@extends('layouts.app')` or similar)
- **CSS Classes**: Use Metronic's predefined CSS classes for consistent styling
- **Components**: Utilize Metronic components like cards (`card`), buttons (`btn btn-primary`), tables (`table table-striped`)
- **Icons**: Use Metronic's icon system (KTIcons, FontAwesome, or Bootstrap Icons)
- **Forms**: Follow Metronic form styling patterns with proper form groups and validation states
- **Modals**: Use Metronic modal components for popups and dialogs
- **DataTables**: Integrate with Metronic's DataTable styling for consistent table appearance
- **Responsive**: Ensure all custom components follow Metronic's responsive breakpoints
- **Theme Colors**: Use Metronic's color palette variables for consistent theming
