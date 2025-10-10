# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Document Structure
1. **Project Overview** - Technology stack and architecture
2. **Development Commands** - Essential commands for development and deployment
3. **Laravel Architecture** - Code organization and patterns
4. **Error Handling** - Logging and debugging techniques
5. **Deployment Workflow** - Server deployment procedures
6. **Application Structure** - Directory organization and features
7. **Testing** - Test organization and best practices
8. **Troubleshooting** - Common issues and solutions
9. **AI Assistant Configuration** - Workflow instructions for AI code agents

## Development Environment
**This project supports both local development (XAMPP/MySQL) and production deployment (Server/PostgreSQL).**
- Local: XAMPP with MySQL database (`DB_CONNECTION=mysql`)
- Production: Server with PostgreSQL (Supabase) database (`DB_CONNECTION=pgsql`)
- Always check `.env` to understand which environment you're working in

## Recent Updates

### October 9, 2025
- **Auction Timer System Rewrite**: Completely rewrote auction countdown timer to keep `endTime` constant in database
  - **Problem**: Previous implementation modified `endTime` in database after each bid, causing countdown to show incorrect values on page refresh
  - **Solution**: Implemented dynamic calculation of final end time without modifying database
  - **Backend Changes** (`app/Http/Controllers/HomeController.php`):
    - Removed `endTime` modification in `placeBid()` method (line 667-672)
    - Added dynamic calculation: `finalEndTime = endTime + (extensionTime * bidsAfterEndTime)`
    - Counts bids placed after original `endTime` to calculate extension
    - Auction ends when: `now >= finalEndTime`
    - Automatically stores `winner_id` (latest bidder's user_id) when auction ends
    - Sets auction `status = 'ended'` when time expires
  - **Frontend Changes** (`resources/views/auction-detail.blade.php`):
    - Receives `bidsAfterEndTime` count from backend
    - Calculates `finalEndTime` using same formula as backend
    - Shows "ENDED" instead of "0s" when countdown reaches zero
    - Displays SweetAlert2 notification and auto-refreshes page when auction ends
  - **Key Formula**: `finalEndTime = endTime + (extensionTime × bidsAfterEndTime)`
  - **Benefits**:
    - `endTime` value in database never changes (predictable, consistent)
    - Correct countdown display on every page refresh
    - Proper winner assignment when auction ends
    - No more "0s" display - shows "ENDED" immediately
  - Files: `app/Http/Controllers/HomeController.php`, `resources/views/auction-detail.blade.php`

- **Admin Auction Create Page Enhancements**: Improved datetime fields and timezone display
  - **Default DateTime Values**: Start time and end time fields now pre-filled with current date/time
    - Start Time: Current time in configured timezone (`now()->format('Y-m-d\TH:i')`)
    - End Time: 24 hours from now (`now()->addDay()->format('Y-m-d\TH:i')`)
    - Timezone indicator: Shows configured timezone below each field (e.g., "Timezone: Asia/Kolkata")
  - **Real-Time Dubai Clock**: Added live clock display in "Pricing & Timing" section header
    - Shows current Asia/Dubai time in real-time (updates every second)
    - Format: "Dubai: Oct 09, 2025, 02:30:45 PM"
    - Uses browser's `Intl.DateTimeFormat` with `timeZone: 'Asia/Dubai'`
    - Displayed on right side of section heading with clock icon
  - **Removed Start Time Validation**: Removed `after:now` validation rule from auction creation
    - Allows creating auctions with any start time (past, present, or future)
    - No longer shows "The start time field must be a date after now" error
    - Validation remains: End time must be after start time
    - Enables backdating auctions or scheduling far in advance
  - Files: `resources/views/admin/auctions/create.blade.php`, `app/Http/Controllers/Admin/AdminAuctionController.php`

### October 8, 2025
- **Admin Panel DataTable Fix**: Fixed DataTable error when no auctions exist in admin panel
  - Changed `@forelse...@empty` to `@foreach` to prevent empty row with `colspan="10"` breaking DataTable initialization
  - DataTable now properly handles empty state with built-in "No auctions found" message
  - File: `resources/views/admin/auctions/index.blade.php`

- **Auctions Listing Page Improvements**: Redesigned filter system and default display order
  - **Default Display**: Shows ALL auctions (no filter applied on first load) in priority order:
    - Priority 0: Active auctions ending within 24 hours (Ending Soon)
    - Priority 1: Other active auctions
    - Priority 2: Upcoming auctions
    - Priority 3: Ended/closed auctions
  - **Filter Options**: Active, Ending Soon, Upcoming, Ended/Closed
  - **New Filter UI**: Search box on left, filter button on right with dropdown modal
    - Filter dropdown contains: Category, Sort By, Status filters
    - Apply Filters button to apply selections
    - Reset button to clear all filters and show all auctions
    - Active filter indicator (dot badge) on filter button
    - Click outside to close dropdown
  - **Filter Button Hover Fix**: Improved visibility with medium gray background (#e9ecef) and darker border
  - File: `app/Http/Controllers/HomeController.php`, `resources/views/auctions.blade.php`

- **Auction Detail Page - Auto-Status Updates**: Fixed auction status not updating based on current time
  - Auction status now auto-updates when viewing detail page:
    - If `now >= endTime + extensionTime`: Set status to `'ended'`
    - If `now >= startTime AND now < endTime + extensionTime`: Set status to `'active'`
    - If `now < startTime`: Set status to `'upcoming'`
  - **Auction End Logic**: Shows "ENDED" only when BOTH conditions met:
    - Condition 1: `endTime + extensionTime < now()`
    - Condition 2: `countdown seconds == 0`
  - Prevents premature "ended" display during extension buffer period
  - Fixed issue where upcoming auctions showed "starts on..." message even after start time passed
  - File: `app/Http/Controllers/HomeController.php:auctionDetail()`

- **Similar Auctions Section Enhancements**: Improved display and filtering
  - **Removed Category Restriction**: Shows auctions from ALL categories (not just same category)
  - **Exclude Ended Auctions**: Only shows active, featured, and upcoming auctions
    - Added checks: `status != 'ended'` AND `endTime > now()`
  - **Display Start/End Times**: Added time display box showing:
    - Start time and end time in format "M d, h:i A" (e.g., "Oct 08, 02:35 PM")
    - Label: "Time:" with single line showing "Start - End"
  - **Fixed Image Sizes**: All auction images constant height (220px) with `object-fit: cover`
  - **Proper Spacing**: 25px gap between cards, 60px section margin-top
  - **Responsive Grid**:
    - Desktop (>1200px): 4 columns
    - Tablet (992px-1200px): 3 columns
    - Small Tablet (768px-992px): 2 columns
    - Mobile (<768px): 1 column
  - **Enhanced Styling**:
    - Card hover effects (lift and shadow)
    - Image zoom on hover
    - Progress bar with gradient
    - Time display in light gray box (#f8f9fa)
    - Yellow underline on "Similar Auctions" heading (#ffdd00)
    - Orange current bid price (#e9922b)
  - Files: `app/Http/Controllers/HomeController.php`, `resources/views/auction-detail.blade.php`

### October 7, 2025
- **Automatic Bidding System**: Implemented automatic bidding functionality on auction detail page
  - Users can set auto-bids with a maximum bid count (`max_bids` in `auto_bids` table)
  - System automatically places bids when last bidder is not the current user
  - Auto-bid decrements `bids_left` with each bid placed
  - Auto-bidding stops when:
    - `bids_left` reaches 0
    - User's `bid_balance` reaches 0
    - Auction ends
  - New API endpoint: `/api/auctions/{auctionId}/auto-bid-status` - Returns auto-bid status and determines if bid should be placed
  - Updated `placeBid()` to accept `is_auto_bid` parameter and decrement auto-bid counts
  - Auto-bid status displayed with animated gradient box showing remaining bids
  - System checks auto-bid conditions every 2 seconds
  - Shows SweetAlert2 notifications when auto-bid is placed or stopped
  - Automatically deactivates auto-bid (`is_active = false`) when user has no bid balance

- **Auction Detail Page Auto-Refresh Control**: Fixed auction detail page to stop auto-refreshing when auction ends
  - Auto-refresh (3-second interval) now only runs for active auctions (`$auction->status === 'active' && $auction->endTime > now()`)
  - Countdown timer automatically stops the auto-refresh interval when auction ends
  - Shows "The auction has ended." message using SweetAlert2 when countdown reaches zero
  - Page reloads once after showing the end message to display final auction state
  - Prevents unnecessary server load from ended auctions constantly refreshing

### October 3, 2025
- **Time Remaining Fix**: Fixed auction detail page to correctly display time remaining countdown
  - Updated `HomeController::auctionDetail()` to properly calculate time remaining using `now()->diffInSeconds($auction->endTime)`
  - Added `formatTimeRemaining()` helper method to format seconds into readable format (Xd Yh Zm As)
  - Fixed progress bar calculation to accurately reflect elapsed time percentage
  - Time now shows "ENDED" only when `now >= endTime`, otherwise displays live countdown
  - Added proper validation for three states: ended (now >= endTime), running (startTime <= now < endTime), upcoming (now < startTime)
  - Updated JavaScript countdown timer to use ISO 8601 date format for better browser compatibility
  - Added auto-refresh every 3 seconds on auction detail page for real-time updates

- **Timezone Configuration**: Set default timezone to 'Asia/Kolkata' in `HomeController` constructor

- **Admin Auction Activation Validation**: Added validation to prevent activating auctions before their start time
  - Frontend validation using SweetAlert2 with beautiful popup messages
  - Backend validation in `AdminAuctionController::updateStatus()` to check `startTime <= now()`
  - Shows detailed error message with time difference (days/hours/minutes) when start time is in the future
  - Displays confirmation dialog before activating auction when validation passes
  - Removed automatic `startTime` modification when activating auctions (startTime remains unchanged)

- **SweetAlert2 Integration**: Added SweetAlert2 library to admin panel for enhanced user notifications
  - Added CSS: `https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css`
  - Added JS: `https://cdn.jsdelivr.net/npm/sweetalert2@11`
  - Used for auction activation validation messages

### June 2025
- **IPv6 Enabled**: Server now supports IPv6 for Supabase PostgreSQL connection
- **Row Level Security**: All database tables have RLS enabled with permissive policies
- **Database**: Successfully migrated from SQLite to PostgreSQL (Supabase hosted)

## Project Overview
Pixelllo is an online auction platform built with Laravel that allows users to bid on products, track their bids, and manage their auction activities. The platform follows a penny auction model where users purchase bid packages and can win high-value items at significant discounts.

## Technology Stack

### Backend
- **Laravel 12.14.1** - PHP web application framework (latest version)
- **PHP 8.4+** - Server-side programming language (requires ^8.4)
- **MySQL/PostgreSQL** - Database (supports both, check .env for current connection)
  - Production: PostgreSQL (Supabase hosted) with Row Level Security (RLS)
  - Development: MySQL (XAMPP local)
- **Laravel Sanctum** - API authentication
- **Eloquent ORM** - Database interactions
- **Stripe** - Payment processing integration

### Frontend
- **Blade Templates** - Laravel's templating engine
- **Vite** - Asset bundling and compilation
- **Tailwind CSS 4.0** - CSS framework
- **JavaScript (ES6+)** - Client-side functionality
- **Axios** - HTTP client for API requests

### Development Tools
- **Composer** - PHP dependency management
- **NPM** - Node.js package management
- **PHPUnit** - PHP testing framework
- **Laravel Pint** - Code formatting
- **Laravel Pail** - Log monitoring

## Common Development Commands

### Essential Laravel Commands
```bash
# Start development server with all services (runs 4 services concurrently)
# - Laravel server (port 8000)
# - Queue worker with single retry
# - Laravel Pail log monitoring
# - Vite dev server for hot reloading
composer dev

# Individual services
php artisan serve                    # Start Laravel server (port 8000)
php artisan queue:listen --tries=1   # Start queue worker
php artisan pail --timeout=0        # Monitor logs in real-time
php artisan pail --filter="error"   # Filter for errors only
npm run dev                          # Start Vite dev server

# Database operations
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed    # Fresh database with seeders
php artisan migrate:rollback         # Rollback last migration
php artisan db:seed                  # Run seeders

# Cache management
php artisan config:clear             # Clear config cache
php artisan cache:clear              # Clear application cache
php artisan view:clear               # Clear compiled views
php artisan route:clear              # Clear route cache

# Custom commands
php artisan bids:process-auto        # Process automatic bids for auctions

# Queue management
php artisan queue:work               # Process queue jobs continuously
php artisan queue:failed             # List failed jobs
php artisan queue:retry {id}         # Retry specific failed job
php artisan queue:retry all          # Retry all failed jobs
php artisan queue:flush              # Delete all failed jobs

# Storage and permissions
php artisan storage:link             # Create storage symlink
sudo chown -R www-data:www-data storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/
```

### Testing Commands
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Unit/DatabaseServiceTest.php

# Run tests with coverage
php artisan test --coverage

# Clear config before testing (IMPORTANT: prevents cached config issues)
php artisan config:clear && php artisan test

# Custom test script from composer.json
composer test                        # Clears config and runs tests
```

### Asset Commands
```bash
# Development
npm run dev                          # Start Vite dev server
npm run build                        # Build for production

# Dependencies
composer install --no-dev --optimize-autoloader  # Production dependencies
npm install                          # Install Node dependencies
```

**Note**: The project uses ES modules (`"type": "module"` in package.json) for modern JavaScript support.

## Laravel Architecture

### MVC Structure
- **Models** (`app/Models/`) - Data layer with Eloquent ORM
- **Controllers** (`app/Http/Controllers/`) - Business logic
- **Views** (`resources/views/`) - Blade templates
- **Routes** (`routes/`) - URL routing definitions

### Key Models and Relationships
- **User** - Authentication, bid balance, roles (user/admin)
- **Auction** - Product auctions with bidding logic
- **Bid** - Individual bids placed by users
- **AutoBid** - Automated bidding functionality
- **Order** - Purchase orders for won auctions
- **BidPackage** - Purchasable bid credits
- **Category** - Product categorization
- **Setting** - Application configuration

### Authentication System
- Laravel Sanctum for API authentication
- Session-based web authentication
- Role-based access control (user/admin)
- Password reset functionality

### Database Configuration
- **Current Connection**: Check `.env` file for `DB_CONNECTION` value
  - `mysql` - Local development (XAMPP)
  - `pgsql` - Production (Supabase hosted)
- Default fallback: `pgsql` (see `config/database.php:19`)
- Testing: SQLite in-memory (see `phpunit.xml:25-26`)
- Migrations in `database/migrations/`
- Seeders in `database/seeders/`
- **IPv6 Required**: Server must have IPv6 enabled for Supabase PostgreSQL connection
- **Row Level Security**: PostgreSQL tables have RLS enabled with permissive policies for database owner

**Note**: When writing database code, ensure compatibility with MySQL, PostgreSQL, and SQLite. The `DatabaseService` class provides driver-agnostic methods. Be aware of driver-specific limitations (e.g., SQLite foreign key constraints, PostgreSQL-specific types).

## Error Logging and Debugging

### Laravel Logs
```bash
# Monitor Laravel logs in real-time
tail -f storage/logs/laravel.log

# Check for specific errors
grep "ERROR" storage/logs/laravel.log

# Laravel Pail - Advanced log monitoring (included in composer dev)
php artisan pail                    # Monitor all logs
php artisan pail --filter="error"   # Filter for errors only
php artisan pail --filter="debug"   # Filter for debug messages
php artisan pail --timeout=0        # Run indefinitely
```

### Common Debug Techniques
```php
// Debug variables at specific points
dd($variable);           // Dump and die
dump($variable);         // Dump and continue

// Database query logging
DB::enableQueryLog();
// ... your code ...
dd(DB::getQueryLog());

// Log custom messages
Log::info('Debug message', ['data' => $variable]);
```

### Common Error Patterns
1. **View errors**: "View [name] not found" - Missing blade templates
2. **Route errors**: "Route [name] not defined" - Check `routes/web.php` or `routes/api.php`
3. **Database errors**: Migration issues or relationship problems
4. **Permission errors**: Storage/cache folder permissions (see commands above)

## Server Deployment Workflow

### Git-Based Deployment
1. **Local Development**: Make changes locally
2. **Git Push**: Push changes to repository
3. **Server Pull**: Pull changes on production server
4. **Apply Updates**: Run deployment commands

### Post-Deployment Checklist
```bash
# 1. Update dependencies (if composer.json changed)
composer install --no-dev --optimize-autoloader

# 2. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 3. Run migrations (if new migrations exist)
php artisan migrate --force

# 4. Storage link (first time only)
php artisan storage:link

# 5. Set permissions
sudo chown -R www-data:www-data storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/

# 6. Test critical functionality
# - Admin panel access
# - Image uploads
# - Database connections
```

### Server Configuration Requirements

#### Nginx Configuration
```nginx
server {
    # File upload settings - CRITICAL for image uploads
    client_max_body_size 10M;
    client_body_buffer_size 10M;
    client_body_timeout 120s;
    client_header_timeout 120s;
    
    location ~ \.php$ {
        # File upload settings for PHP
        fastcgi_read_timeout 120s;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }
}
```

#### PHP Configuration
```ini
; File Upload Settings
upload_max_filesize = 10M
post_max_size = 12M
max_file_uploads = 20
file_uploads = On

; Memory and Execution Settings
memory_limit = 256M
max_execution_time = 120
max_input_time = 120
```

## Application Structure

### Core Features
- **Public Auction Pages** - Browse and bid on active auctions
- **User Dashboard** - Manage bids, wins, orders, and account settings
- **Admin Panel** - Manage auctions, users, categories, and site settings
- **Real-time Updates** - Live bidding and countdown timers
- **Payment Processing** - Bid package purchases and auction payments

### Key Directories
- `app/Http/Controllers/` - Request handling logic
  - `API/` - API endpoints for AJAX requests
  - `Admin/` - Admin panel controllers
- `app/Models/` - Eloquent models with relationships
- `app/Services/` - Business logic services (e.g., DatabaseService)
- `resources/views/` - Blade templates
  - `admin/` - Admin panel views
  - `dashboard/` - User dashboard views
  - `components/` - Reusable components
- `routes/` - URL routing
  - `web.php` - Web routes
  - `api.php` - API routes
- `database/migrations/` - Database schema changes
- `database/seeders/` - Sample data creation

### Helper Functions
- `app/Helpers/helpers.php` - Global helper functions (autoloaded via composer.json)
- `app/Helpers/mbstring_polyfill.php` - Multibyte string function polyfills
- `app/Helpers/CurrencyHelper.php` - Currency formatting utilities
- Helpers are autoloaded through composer.json's "files" section:
  ```json
  "autoload": {
      "files": [
          "app/Helpers/mbstring_polyfill.php",
          "app/Helpers/helpers.php"
      ]
  }
  ```

### Payment Integration
- **Stripe**: Configured for bid package purchases and auction payments
  - API keys stored in `.env` (STRIPE_KEY, STRIPE_SECRET)
  - Configuration: `config/stripe.php`
  - Controller: `app/Http/Controllers/StripeController.php`

### Currency Support
- Multi-currency support via `config/currencies.php`
- Currency selection and conversion functionality
- Controller: `app/Http/Controllers/CurrencyController.php`

## Testing Structure

### Test Organization
- `tests/Unit/` - Unit tests for individual components
- `tests/Feature/` - Feature tests for complete workflows
- Uses PHPUnit testing framework
- SQLite in-memory database for testing isolation

### Running Tests
```bash
# All tests
php artisan test

# Specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Single test file
php artisan test tests/Unit/DatabaseServiceTest.php

# With coverage
php artisan test --coverage
```

## Development Best Practices

### Laravel Conventions
- Use Eloquent relationships instead of manual joins
- Follow PSR-4 autoloading standards
- Use Laravel's built-in validation rules
- Implement proper exception handling
- Use Laravel's caching mechanisms appropriately

### Security Considerations
- Always validate user input using Laravel's validation
- Use CSRF protection for forms
- Implement proper authorization checks
- Sanitize data before database operations
- Use Laravel Sanctum for API authentication

### Performance Guidelines
- Use eager loading to prevent N+1 queries
- Implement proper database indexing
- Cache expensive operations
- Optimize image uploads and storage
- Use Laravel's queue system for heavy operations

## Troubleshooting Common Issues

### 413 Request Entity Too Large
- **Cause**: Nginx `client_max_body_size` too small
- **Fix**: Update nginx config and reload

### 500 Internal Server Error on Upload
- **Cause**: PHP upload limits or execution time
- **Fix**: Update PHP configuration and restart PHP-FPM

### Images Not Displaying
- **Cause**: Missing storage symlink or permissions
- **Fix**: `php artisan storage:link` and set proper permissions

### Database Connection Issues
- **Cause**: Database connection problems (MySQL or PostgreSQL)
- **Fix**: Check `.env` database credentials and ensure database service is running
  - Local (MySQL): Verify XAMPP MySQL service is running
  - Production (PostgreSQL): Check Supabase connection and credentials
- **IPv6 Network Issues** (PostgreSQL/Supabase only):
  - **Cause**: Supabase requires IPv6, server only has IPv4
  - **Fix**: Enable IPv6 on server (see IPv6 Configuration section below)
- **RLS Policy Errors** (PostgreSQL only):
  - **Cause**: Row Level Security blocking access
  - **Fix**: Ensure database user has proper permissions or policies

### Cache Issues
- **Cause**: Stale configuration or view cache
- **Fix**: Clear all caches using artisan commands above

### IPv6 Configuration (DigitalOcean)
```bash
# Add IPv6 to network interface (get IPv6 from DigitalOcean metadata)
curl -s http://169.254.169.254/metadata/v1.json | grep -A10 -B10 ipv6

# Add IPv6 address (replace with your actual IPv6)
sudo ip -6 addr add YOUR_IPV6_ADDRESS/64 dev eth0
sudo ip -6 route add default via YOUR_IPV6_GATEWAY dev eth0

# Make permanent by editing /etc/network/interfaces
# Add under eth0 configuration:
# iface eth0 inet6 static
#     address YOUR_IPV6_ADDRESS
#     netmask 64
#     gateway YOUR_IPV6_GATEWAY
```

### Row Level Security (RLS) Management
```bash
# Check RLS status on all tables
php artisan tinker --execute="DB::select('SELECT tablename, rowsecurity FROM pg_tables WHERE schemaname = \'public\'')"

# Migration to enable RLS (already applied)
# See: database/migrations/2025_06_25_133146_enable_rls_on_all_tables.php
```

## AI Assistant Configuration

The following section provides specific workflow instructions for AI code assistants working with this repository. These instructions ensure consistent, high-quality development practices.

---

# AI Code Agent Development Prompt

## Core Principles

You are an AI code agent that follows strict development practices. Your primary goals are:
- Write clean, maintainable, and modular code
- Follow a structured workflow with clear checkpoints
- Ensure all code is tested before completion
- Maintain small, focused functions and files

## Workflow Process

### 1. Understanding Phase (MANDATORY - DO NOT SKIP)
When I provide a task, you MUST:

1. **Analyze the requirements** and create an understanding statement that includes:
   - **Objective**: What needs to be built
   - **Key Features**: List of main functionalities
   - **Technical Approach**: Technologies and architecture
   - **File Structure**: Proposed organization
   - **Task Breakdown**: List of small, manageable subtasks
   - **Success Criteria**: How we'll know it's complete

2. **Wait for my confirmation** with phrases like "proceed", "go ahead", or "looks good"
3. **DO NOT start coding** until I explicitly confirm the understanding

### 2. Development Phase
Once I confirm, follow these rules:

#### Code Quality Standards
- **Function Size**: Each function should be < 20 lines
- **File Size**: Each file should be < 100 lines
- **Single Responsibility**: Each function/class does ONE thing
- **Clear Naming**: Use descriptive names for variables, functions, and files
- **Comments**: Add comments only for complex logic
- **DRY Principle**: Don't Repeat Yourself

#### Task Management
- Work on one subtask at a time
- Show progress after completing each subtask
- Ask for clarification if needed
- Present code in logical chunks

### 3. Testing Phase
For EVERY piece of functionality:

1. **Write unit tests** before or alongside the code
2. **Show test results** - all tests must pass
3. **Include edge cases** in testing
4. **Test file naming**: `test_[feature_name].php` or `[feature_name].test.js`

### 4. Review Phase
After coding each subtask:
1. Present the code for review
2. Show test results
3. Wait for my feedback
4. Make adjustments if needed

### 5. Git Commit Phase
Only when I say "good to go" or similar approval:
1. Prepare a meaningful commit message
2. Show the git commands to be executed
3. Wait for final confirmation before considering the task complete

## Example Interaction Flow

```
User: "Create a user authentication system with email and password"

AI: 
"I understand you want me to create a user authentication system. Here's my understanding:

**Objective**: Build a user authentication system with email/password functionality

**Key Features**:
- User registration
- User login
- Password hashing
- Session management
- Input validation

**Technical Approach**:
- Language: [PHP/Laravel]
- Framework: [Laravel 12]
- Database: [PostgreSQL]
- Password hashing: bcrypt
- Session handling: Laravel Sanctum

**File Structure**:
```
/auth
  ├── Controllers/
  │   ├── RegisterController.php
  │   └── LoginController.php
  ├── Models/
  │   └── User.php
  ├── Requests/
  │   ├── RegisterRequest.php
  │   └── LoginRequest.php
  ├── tests/
  │   ├── Feature/
  │   │   ├── RegisterTest.php
  │   │   └── LoginTest.php
  │   └── Unit/
  │       └── UserTest.php
  └── routes/
      └── auth.php
```

**Task Breakdown**:
1. Create user model with email and password fields
2. Implement registration controller and validation
3. Create login controller with authentication
4. Add password hashing and verification
5. Implement JWT token generation with Sanctum
6. Write comprehensive tests
7. Update routes

**Success Criteria**:
- Users can register with valid email/password
- Passwords are securely hashed
- Users can login and receive authentication token
- All tests pass
- Code follows Laravel and clean code principles

Please confirm if this understanding is correct and I should proceed."

User: "Proceed"

AI: [Starts coding following the workflow]
```

## Important Reminders

1. **ALWAYS wait for understanding confirmation** before coding
2. **Break down large tasks** into subtasks of 1-2 hours each
3. **Test everything** - no code without tests
4. **Keep functions small** - if it's getting big, split it
5. **One file, one purpose** - maintain clear separation
6. **Progress updates** - show what's completed after each subtask
7. **Git commits** only after explicit approval

## Response Format

When presenting code, use this structure:
```
### Subtask: [Name]

**File**: [filename]
```[language]
[code]
```

**Tests**: [test_filename]
```[language]
[test code]
```

**Test Results**:
✓ Test 1: [description] - PASSED
✓ Test 2: [description] - PASSED

**Next**: [What comes next]
```

Remember: Quality over speed. Clean, tested code is the goal.