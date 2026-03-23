### Personalized Video Campaign Manager
Personalised video campaign management system 

## Features

Asynchronous Processing: Immediate 202 Accepted response; data storage is offloaded to background workers.

Duplicate Protection: Auto of dupliyyne user_id per campaign with a dedicated logging system for transparency.

Flexible Schema: Supports arbitrary custom_fields using MySQL JSON storage.

Rate Limiting: Protects the ingestion engine from spam and resource exhaustion.

Dockerized: Fully containerized environment (App, Database, and Queue Worker).

# Tech Stack

    Framework: Laravel 12.x

    Language: PHP 8.x

    Database: MySQL 8.0

    Queue: Database Driver

    Containerization: Docker & Docker Compose

## Installation & Setup
git clone <your-repo-url>
cd chickpea-backend
cp .env.example .env

# 2. Build and Start Containers

This command builds the app image and starts the Web Server, MySQL database, and the Background Worker.

docker-compose up -d --build

# 3. Initialize Application

docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate

## API Documentation

Authentication

/api/register
/api/login

All protected routes require a Bearer Token via Laravel Sanctum.

/api/logout 

1. Create Campaign

POST /api/campaigns

    Body: client_id, name, start_date, end_date

    Response: 201 Created

2. Add Campaign Data (Async)

POST /api/campaigns/{campaign_id}/data

    Rate Limit: 10 requests per minute.

##  Background Job System

This project uses Laravel Queues to ensure the API remains responsive

# Event: When data hits the controller, a CampaignDataReceived event is dispatch
# Queue: The event is stored in the jobs table.
# Worker: The chickpea-worker container continuously monitors this table and executes the CampaignDataJob listener to perform database inserts and duplicate checks.

# To monitor the worker logs in real-time: 
docker-compose logs -f worker

## command line functionality 
# Analytics & Reporting

docker-compose exec app php artisan campaign:report {campaign_id}

## Testing
# A Postman collection is included in the root directory:
Chickpea CMS.postman_collection.json

1 Import the JSON into Postman.

2 Set the base_url variable to http://localhost:8000.

3 Run the Login request first to populate the Bearer Token.
