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
- **Key packages**: DomPDF, Excel export, Barcode/QR generation, DataTables, Captcha

## Code Style & Conventions
- **PSR-4 autoloading**: `App\` namespace for main app, `Modules\` for modules
- **Helpers**: Custom helpers in `app/Helpers/` (DateHelper.php, Random.php)
- **Migrations**: Use descriptive names, follow Laravel conventions
- **Controllers**: Use resource controllers, proper HTTP methods
- **Models**: Use Eloquent ORM, define relationships clearly
- **Views**: Blade templates, use components for reusability
- **Testing**: PHPUnit with Feature/Unit test separation
