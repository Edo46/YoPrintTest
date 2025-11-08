# Laravel CSV Upload System with Real-Time Updates# CSV File Upload System<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



A production-ready Laravel 12 application demonstrating CSV file upload processing with real-time progress updates using WebSockets. Built for handling large datasets efficiently with background processing and live frontend notifications.



## FeaturesA Laravel 12 application for uploading and processing CSV files with real-time status updates using background job processing.<p align="center">



- **Drag-and-Drop CSV Upload**: Modern, user-friendly file upload interface<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>

- **Background Processing**: Queue-based CSV processing with Laravel Horizon

- **Real-Time Updates**: Live progress notifications via Laravel Reverb (WebSockets)## Features<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>

- **Large Dataset Support**: Handles 36,000+ rows efficiently (~30 seconds processing time)

- **Idempotent Uploads**: UPSERT logic allows re-uploading the same file safely<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>

- **UTF-8 Data Cleaning**: Automatic removal of invalid UTF-8 characters

- **41 Product Columns**: Complete product data structure with all attributes✅ **Drag & Drop File Upload** - Modern UI with drag-and-drop and button upload  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>

- **SQLite Database**: Lightweight database for development and testing

- **Production-Ready**: Clean code without debug logging, WebSocket-only updates✅ **Background Processing** - Laravel Horizon with Redis queue  </p>



## Technical Stack✅ **Real-time Updates** - WebSocket broadcasting with Laravel Reverb  



- **Framework**: Laravel 12.37.0 (Monolith architecture)✅ **Idempotent Uploads** - Prevents duplicate entries using file hashing  ## About Laravel

- **PHP**: 8.2.28

- **Database**: SQLite✅ **UPSERT Operations** - Updates existing records based on UNIQUE_KEY  

- **Queue**: Redis with Laravel Horizon 5.39.0

- **Broadcasting**: Laravel Reverb 1.6.0 (WebSocket server)✅ **UTF-8 Cleaning** - Automatically removes non-UTF-8 characters  Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- **Frontend**: Vanilla JavaScript with Laravel Echo + Pusher-js

- **Build Tool**: Vite 7.2.2✅ **API Resources** - RESTful API with proper transformers  



## Architecture✅ **Upload History** - View all uploads with status and progress tracking  - [Simple, fast routing engine](https://laravel.com/docs/routing).



### Backend- [Powerful dependency injection container](https://laravel.com/docs/container).

- **Models**: `FileUpload`, `Product`, `User`

- **Jobs**: `ProcessCsvUpload` (background CSV processing)## Requirements- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.

- **Events**: `FileUploadStatusChanged` (real-time broadcast)

- **Queue**: Redis-backed with Horizon monitoring- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).

- **Storage**: Laravel 12's private disk (`storage/app/private/`)

- PHP 8.2 or higher- Database agnostic [schema migrations](https://laravel.com/docs/migrations).

### Frontend

- **Echo Client**: WebSocket connection to Reverb- Composer- [Robust background job processing](https://laravel.com/docs/queues).

- **Real-Time Listener**: Custom event dispatcher for progress updates

- **Transport**: WebSocket-only (no HTTP polling fallback)- Node.js & NPM- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

- **UI**: Progress bars updating live without page refresh

- **Update Strategy**: Direct DOM manipulation from WebSocket events (no HTTP polling)- Redis server



## Prerequisites- SQLite (default) or other databaseLaravel is accessible, powerful, and provides tools required for large, robust applications.



- PHP 8.2 or higher

- Composer

- Node.js and npm## Installation## Learning Laravel

- Redis server

- PHP Redis extension (`phpredis`)



## Installation### 1. Clone the repositoryLaravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.



### 1. Clone and Install Dependencies



```bash```bashIf you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

# Clone the repository

git clone <repository-url>git clone <repository-url>

cd yo-print-test

cd yo-print-test## Laravel Sponsors

# Install PHP dependencies

composer install```



# Install Node dependenciesWe would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

npm install

```### 2. Install dependencies



### 2. Environment Configuration### Premium Partners



```bash```bash

# Copy environment file

cp .env.example .envcomposer install- **[Vehikl](https://vehikl.com)**



# Generate application keynpm install- **[Tighten Co.](https://tighten.co)**

php artisan key:generate

``````- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**



### 3. Configure Environment Variables- **[64 Robots](https://64robots.com)**



Edit `.env` with the following critical settings:### 3. Environment setup- **[Curotec](https://www.curotec.com/services/technologies/laravel)**



```env- **[DevSquad](https://devsquad.com/hire-laravel-developers)**

# Application

APP_NAME="CSV Upload System"```bash- **[Redberry](https://redberry.international/laravel-development)**

APP_ENV=local

APP_DEBUG=truecp .env.example .env- **[Active Logic](https://activelogic.com)**

APP_URL=http://localhost:8001

php artisan key:generate

# Database (SQLite)

DB_CONNECTION=sqlite```## Contributing

DB_DATABASE=/absolute/path/to/database/database.sqlite



# Queue & Broadcasting

QUEUE_CONNECTION=redisUpdate your `.env` file with Redis configuration:Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

BROADCAST_CONNECTION=reverb



# Redis

REDIS_HOST=127.0.0.1```env## Code of Conduct

REDIS_PASSWORD=null

REDIS_PORT=6379QUEUE_CONNECTION=redis



# Reverb WebSocket Server (Backend)BROADCAST_CONNECTION=reverbIn order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

REVERB_APP_ID=2

REVERB_APP_KEY=mjiec3xemiwcsyyvepdp

REVERB_APP_SECRET=rznfczdixukifotfgjfi

REVERB_HOST="127.0.0.1"REDIS_CLIENT=phpredis## Security Vulnerabilities

REVERB_PORT=8080

REVERB_SCHEME=httpREDIS_HOST=127.0.0.1



# Vite Frontend Variables (MUST be hardcoded, no ${} references)REDIS_PASSWORD=nullIf you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

VITE_APP_NAME="${APP_NAME}"

VITE_REVERB_APP_KEY=mjiec3xemiwcsyyvepdpREDIS_PORT=6379

VITE_REVERB_HOST=localhost

VITE_REVERB_PORT=8080```## License

VITE_REVERB_SCHEME="http"

```



**⚠️ Important**: `VITE_*` variables must use literal values, NOT `${VAR}` references. Vite does not expand environment variable references at build time.### 4. Database setupThe Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



### 4. Database Setup

```bash

```bashphp artisan migrate

# Create SQLite database file```

touch database/database.sqlite

### 5. Build frontend assets

# Run migrations

php artisan migrate```bash

```npm run build

```

### 5. Build Frontend Assets

For development with hot reload:

```bash```bash

npm run buildnpm run dev

``````



## Running the Application## Running the Application



You need **three terminal windows** running simultaneously:You'll need to run **4 separate terminal windows**:



### Terminal 1: Laravel Development Server### Terminal 1: Application Server

```bash

```bashphp artisan serve

php artisan serve```

# Starts on http://127.0.0.1:8001 (or port 8000 if available)Application will be available at: http://localhost:8000

```

### Terminal 2: Queue Worker (Horizon)

### Terminal 2: Laravel Reverb (WebSocket Server)```bash

php artisan horizon

```bash```

php artisan reverb:start --debugHorizon dashboard available at: http://localhost:8000/horizon

# Starts WebSocket server on port 8080

```### Terminal 3: WebSocket Server (Reverb)

```bash

### Terminal 3: Laravel Horizon (Queue Worker)php artisan reverb:start

```

```bashWebSocket server runs on port 8080

php artisan horizon

# Starts Redis queue processor### Terminal 4: Redis Server (if not running as service)

``````bash

redis-server

## Usage```



1. **Access Application**: Navigate to `http://localhost:8001/file-uploads`## Usage



2. **Upload CSV**: 1. Open your browser to `http://localhost:8000`

   - Drag and drop a CSV file into the upload area2. Upload a CSV file using drag & drop or the "Browse Files" button

   - Or click to browse and select a file3. Watch real-time status updates as your file is processed

   - CSV must have 41 columns matching the product schema4. View upload history with status indicators



3. **Real-Time Progress**:## CSV File Format

   - Progress updates broadcast every 100 rows processed via WebSocket

   - UI updates in real-time without any HTTP pollingYour CSV file should have the following columns:

   - Status changes from "processing" to "completed" live

   - No page refresh needed!```

UNIQUE_KEY,PRODUCT_TITLE,PRODUCT_DESCRIPTION,STYLE#,SANMAR_MAINFRAME_COLOR,SIZE,COLOR_NAME,PIECE_PRICE

4. **Monitor Jobs**: Visit `http://localhost:8001/horizon` to see:```

   - Job processing metrics

   - Queue status### Example:

   - Event broadcasting success/failure```csv

   - Processing timesUNIQUE_KEY,PRODUCT_TITLE,PRODUCT_DESCRIPTION,STYLE#,SANMAR_MAINFRAME_COLOR,SIZE,COLOR_NAME,PIECE_PRICE

ABC123,Product Name,Product Description,STY001,Blue,L,Navy Blue,29.99

## CSV FormatDEF456,Another Product,Another Description,STY002,Red,M,Crimson,24.99

```

The system expects CSV files with these 41 columns:

## API Endpoints

```

id, option1_name, option1_value, option2_name, option2_value, option3_name, ### List all uploads

option3_value, sku, barcode, has_unique_prices, price, compare_price, cost_price, ```

requires_shipping, fulfillment_service, inventory_management, GET /api/v1/file-uploads

inventory_quantity_adjustment, inventory_policy, inventory_tracker, ```

weight_value, weight_unit, country_of_origin, province_of_origin, 

harmonized_system_code, material, variant_image, variant_tax_code, ### Upload new file

variant_id, product_id, product_handle, product_title, product_type, ```

product_vendor, product_tags, product_published, product_option1, POST /api/v1/file-uploads

product_option2, product_option3, product_image, product_status, Content-Type: multipart/form-data

product_seo_title, product_seo_description

```Body: file=<csv file>

```

## Configuration Details

### Get specific upload

### WebSocket Connection```

GET /api/v1/file-uploads/{id}

The frontend connects via:```

- **Protocol**: `ws://` (not `wss://` for local development)

- **Host**: `localhost` (not a custom domain)## Database Schema

- **Port**: `8080`

- **Transport**: WebSocket only (`['ws']`), no HTTP polling### file_uploads table

- `id` - Primary key

### Real-Time Update Flow- `original_filename` - Original file name

- `stored_filename` - Stored file name

1. Job processes CSV in background (Horizon)- `file_path` - Path to stored file

2. Event broadcasts via Reverb WebSocket every 100 rows- `file_hash` - MD5 hash for idempotency

3. Echo client receives WebSocket message- `status` - pending, processing, completed, failed

4. Custom event dispatcher forwards to Blade template- `total_rows` - Total rows in CSV

5. DOM updates directly from event data (no HTTP request)- `processed_rows` - Rows processed so far

- `error_message` - Error details if failed

### Why Not Laravel Valet?- `created_at`, `updated_at`



During development, we discovered that Laravel Valet's custom domain (`.test`) interferes with WebSocket connections on non-standard ports. Using `php artisan serve` with `localhost` resolves all connection issues.### products table

- `id` - Primary key

### Environment Variable Gotcha- `unique_key` - Unique identifier from CSV (indexed)

- `product_title` - Product title

**Problem**: Vite doesn't expand `${VAR}` references in `.env` files.- `product_description` - Product description

- `style` - Style number

**Bad**:- `sanmar_mainframe_color` - Color from mainframe

```env- `size` - Product size

VITE_REVERB_PORT=${REVERB_PORT}  # Won't work!- `color_name` - Color name

```- `piece_price` - Price per piece (decimal)

- `file_upload_id` - Foreign key to file_uploads

**Good**:- `created_at`, `updated_at`

```env

VITE_REVERB_PORT=8080  # Hardcoded value works## Key Features Explained

```

### Idempotency

Always use literal values for `VITE_*` variables and rebuild assets after changes.Files are hashed using MD5. If you upload the same file twice, the system recognizes it and re-processes instead of creating a duplicate entry.



## Performance### UPSERT Logic

Products are updated based on `UNIQUE_KEY`. If a product with the same `UNIQUE_KEY` exists, it will be updated with new values. Otherwise, a new record is created.

- **Processing Speed**: ~36,663 rows in 29-32 seconds

- **Batch Size**: Progress broadcasts every 100 rows### UTF-8 Cleaning

- **Event Latency**: 1-10ms from Horizon to ReverbAll CSV data is automatically cleaned of:

- **Real-Time Latency**: Near-instant frontend updates (<100ms)- BOM (Byte Order Mark)

- **Network Efficiency**: WebSocket-only, no HTTP polling overhead- Invalid UTF-8 characters

- Control characters

## Troubleshooting

### Real-time Updates

### WebSocket Connection FailsThe UI polls the API every 3 seconds for updates. When jobs update upload status, the changes are reflected immediately in the UI.



**Symptoms**: Browser console shows connection errors, "unavailable" state### Background Processing

All CSV processing happens in background jobs via Laravel Horizon. This prevents timeouts and allows processing of large files.

**Solutions**:

1. Verify Reverb is running: `php artisan reverb:start --debug`## Troubleshooting

2. Check `.env` has correct ports (8080 for Reverb)

3. Ensure `VITE_*` variables use literal values, not `${}`### Queue not processing

4. Rebuild assets after `.env` changes: `npm run build`- Ensure Redis is running: `redis-cli ping` should return `PONG`

5. Hard refresh browser: `Cmd+Shift+R` (Mac) or `Ctrl+Shift+F5` (Windows)- Check Horizon is running: `php artisan horizon`

6. Use `localhost`, not custom domains like `.test`- Check queue connection in `.env`: `QUEUE_CONNECTION=redis`



### No Real-Time Updates### WebSocket not connecting

- Ensure Reverb is running: `php artisan reverb:start`

**Symptoms**: File uploads but progress doesn't update- Check Reverb configuration in `.env`

- Verify WebSocket port (8080) is not blocked

**Solutions**:

1. Check all three services are running (serve, reverb, horizon)### File upload fails

2. Verify Horizon shows events as "DONE", not "FAIL"- Check `storage/app/uploads` directory exists and is writable

3. Check browser console for WebSocket connection status- Verify PHP `upload_max_filesize` and `post_max_size` settings

4. Ensure Redis is running: `redis-cli ping` should return `PONG`- Check application logs: `storage/logs/laravel.log`

5. Check `forceTLS: false` in `resources/js/echo.js`

6. Verify event listener receives data: add `console.log` temporarily## Testing



### HTTP Polling Instead of WebSocketRun the test suite:

```bash

**Symptoms**: Network tab shows HTTP requests to `/file-uploads` during processingphp artisan test

```

**Solution**: This was fixed by updating the event listener to directly manipulate the DOM instead of calling `fetchUploads()`. Hard refresh browser to load the updated code.

## Monitoring

### Redis Extension Missing

### Horizon Dashboard

**Symptoms**: Horizon fails to start with Redis connection errorsAccess at `http://localhost:8000/horizon` to monitor:

- Job throughput

**Solution**:- Failed jobs

```bash- Job metrics

# Check if phpredis is installed- Queue workload

php -m | grep redis

### Logs

# If missing, install via PECLApplication logs are in `storage/logs/laravel.log`

pecl install redis

## Production Considerations

# Restart PHP-FPM (if using Laravel Valet)

valet restart1. **Queue Workers**: Use Supervisor to keep Horizon running

```2. **WebSockets**: Consider using a reverse proxy (nginx) for Reverb

3. **Redis**: Use persistent Redis configuration

### Queue Not Processing4. **File Storage**: Consider using S3 or other cloud storage for production

5. **Security**: Add authentication/authorization middleware

**Symptoms**: Upload succeeds but job never processes6. **Rate Limiting**: Implement rate limiting on upload endpoints



**Solutions**:## Tech Stack

1. Check Horizon is running and showing "Master Supervisor Running"

2. Verify queue connection: `php artisan queue:work --once`- **Backend**: Laravel 12 (PHP 8.2+)

3. Check `QUEUE_CONNECTION=redis` in `.env`- **Queue**: Laravel Horizon + Redis

4. Clear queue failed jobs: `php artisan queue:clear`- **Database**: SQLite (configurable)

- **WebSockets**: Laravel Reverb

## Development Tips- **Frontend**: Vanilla JavaScript with modern CSS

- **Build Tool**: Vite

### After Changing `.env`

## License

```bash

# Rebuild frontend assets (VITE_* variables changed)Open-sourced software licensed under the MIT license.

npm run build

# Restart Reverb (REVERB_* variables changed)
# Ctrl+C in Reverb terminal, then:
php artisan reverb:start --debug

# Hard refresh browser
# Cmd+Shift+R (Mac) or Ctrl+Shift+F5 (Windows)
```

### After Changing Blade Templates

Blade templates with inline JavaScript don't require `npm run build`. Just hard refresh the browser.

### Monitoring Tools

- **Horizon Dashboard**: `http://localhost:8001/horizon`
- **Redis CLI**: `redis-cli monitor` (watch all Redis operations)
- **Browser DevTools**: Network tab > WS filter (watch WebSocket frames)
- **Reverb Debug**: Look for connection logs in the Reverb terminal

### Clear Everything

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset queue
php artisan queue:clear
php artisan horizon:terminate

# Clear database
php artisan migrate:fresh

# Rebuild assets
npm run build
```

## Project Structure

```
├── app/
│   ├── Events/
│   │   └── FileUploadStatusChanged.php    # Real-time broadcast event
│   ├── Jobs/
│   │   └── ProcessCsvUpload.php           # Background CSV processor
│   └── Models/
│       ├── FileUpload.php                 # Upload metadata
│       └── Product.php                    # Product data (41 columns)
├── database/
│   └── migrations/
│       ├── 2025_11_07_235506_create_file_uploads_table.php
│       └── 2025_11_07_235513_create_products_table.php
├── resources/
│   ├── js/
│   │   ├── app.js                         # Echo listener setup
│   │   └── echo.js                        # Reverb configuration
│   └── views/
│       └── file-uploads.blade.php         # Upload interface + real-time updates
├── routes/
│   └── web.php                            # Application routes
└── public/
    └── build/                             # Compiled assets
```

## Key Files

### Backend

- **`app/Jobs/ProcessCsvUpload.php`**: Core CSV processing logic with UPSERT and broadcasting
- **`app/Events/FileUploadStatusChanged.php`**: Event for real-time status updates
- **`app/Models/Product.php`**: Product model with all 41 fillable fields
- **`config/broadcasting.php`**: Reverb connection configuration
- **`config/horizon.php`**: Horizon queue configuration

### Frontend

- **`resources/js/echo.js`**: Echo client with WebSocket-only transport
- **`resources/js/app.js`**: Channel listener and custom event dispatcher
- **`resources/views/file-uploads.blade.php`**: Upload UI with real-time progress (inline JS)

## Technical Highlights

### WebSocket-Only Architecture

Unlike traditional polling approaches, this system uses WebSockets exclusively:

- **No HTTP Polling**: Frontend doesn't make repeated HTTP requests
- **Push Model**: Server pushes updates to client in real-time
- **Efficient**: Single persistent connection instead of multiple HTTP requests
- **Scalable**: Reduces server load significantly for real-time features

### Direct DOM Updates

The real-time update handler directly manipulates the DOM:

```javascript
window.addEventListener('file-upload-updated', (event) => {
    const upload = event.detail;
    // Find and update the specific DOM element
    // No HTTP request, no page refresh
});
```

This approach is:
- **Fast**: Updates appear instantly
- **Efficient**: No unnecessary data fetching
- **Clean**: Event-driven architecture

## License

This project is built with Laravel, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
