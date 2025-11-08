#!/bin/bash

# CSV Upload System - Setup Script
# This script automates the setup process

set -e

echo "🚀 CSV Upload System - Setup Script"
echo "===================================="
echo ""

# Check PHP version
echo "📋 Checking prerequisites..."
php_version=$(php -r 'echo PHP_VERSION;')
echo "✓ PHP version: $php_version"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi
echo "✓ Composer is installed"

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    exit 1
fi
echo "✓ NPM is installed"

# Check if redis is running
if ! redis-cli ping &> /dev/null; then
    echo "⚠️  Warning: Redis is not running. You'll need to start it later."
    echo "   Run: redis-server"
else
    echo "✓ Redis is running"
fi

echo ""
echo "📦 Installing PHP dependencies..."
composer install --no-interaction

echo ""
echo "📦 Installing Node dependencies..."
npm install

echo ""
echo "⚙️  Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✓ Created .env file"
else
    echo "✓ .env file already exists"
fi

echo ""
echo "🔑 Generating application key..."
php artisan key:generate --no-interaction

echo ""
echo "🗄️  Setting up database..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "✓ Created SQLite database"
else
    echo "✓ SQLite database already exists"
fi

echo ""
echo "📊 Running migrations..."
php artisan migrate --no-interaction

echo ""
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "✅ Setup complete!"
echo ""
echo "🎯 To start the application, run these commands in separate terminals:"
echo ""
echo "   Terminal 1: php artisan serve"
echo "   Terminal 2: php artisan horizon"
echo "   Terminal 3: php artisan reverb:start"
echo "   Terminal 4: redis-server (if not running)"
echo ""
echo "Then open: http://localhost:8000"
echo ""
echo "📖 See QUICKSTART.md for more details"
echo ""
