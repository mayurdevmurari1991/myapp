#!/bin/bash

# Install dependencies
composer install

# Copy .env.example to .env if .env does not exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Optional: Run database migrations and seeds with a single command
php artisan migrate --seed
