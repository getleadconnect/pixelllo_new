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

## IMPORTANT: Server-Only Development
**This project is deployed on a production server. All fixes and updates should be focused on server deployment only. Do not test or configure for local development environments.**

## Recent Updates (June 2025)
- **IPv6 Enabled**: Server now supports IPv6 for Supabase PostgreSQL connection
- **Row Level Security**: All database tables have RLS enabled with permissive policies
- **Database**: Successfully migrated from SQLite to PostgreSQL (Supabase hosted)

## Project Overview
Pixelllo is an online auction platform built with Laravel that allows users to bid on products, track their bids, and manage their auction activities. The platform follows a penny auction model where users purchase bid packages and can win high-value items at significant discounts.

## Technology Stack

### Backend
- **Laravel 12.14.1** - PHP web application framework (latest version)
- **PHP 8.2+** - Server-side programming language
- **PostgreSQL** - Primary database (Supabase hosted)
- **Laravel Sanctum** - API authentication
- **Eloquent ORM** - Database interactions
- **Row Level Security (RLS)** - Enabled on all tables for API security

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
- Primary: PostgreSQL (Supabase hosted)
- Default connection: `pgsql` (see `config/database.php:19`)
- Testing: SQLite in-memory (see `phpunit.xml:25-26`)
- Migrations in `database/migrations/`
- Seeders in `database/seeders/`
- **IPv6 Required**: Server must have IPv6 enabled for Supabase connection
- **Row Level Security**: All tables have RLS enabled with permissive policies for database owner

**Note**: When writing database code, ensure compatibility with both PostgreSQL and SQLite. The `DatabaseService` class provides driver-agnostic methods. Be aware of SQLite limitations when writing tests (e.g., foreign key constraints, certain data types).

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
- `app/Helpers/CurrencyHelper.php` - Currency formatting utilities
- Helpers are autoloaded through composer.json's "files" section:
  ```json
  "autoload": {
      "files": ["app/Helpers/helpers.php"]
  }
  ```

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
- **Cause**: PostgreSQL connection problems
- **Fix**: Check `.env` database credentials and PostgreSQL service status
- **IPv6 Network Issues**: 
  - **Cause**: Supabase requires IPv6, server only has IPv4
  - **Fix**: Enable IPv6 on server (see IPv6 Configuration section below)
- **RLS Policy Errors**:
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