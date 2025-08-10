# Simple Search API

A Laravel-based API that aggregates content from multiple providers (XML and JSON), normalizes and scores the content based on relevance, popularity, and recency, caches the results in Redis, and exposes a searchable, paginated API.  
Includes a simple dashboard built with Laravel Blade and DataTables to display the aggregated content.

---

## Features

- Fetch content from two different mock providers (XML and JSON).
- Normalize and unify different data formats.
- Calculate a relevance score based on views, likes, reading time, freshness, and keyword matches.
- Rate limit provider requests to avoid overloading.
- Cache results in Redis for fast response and reduced API calls.
- Serve stale cached data if providers are down or unreachable.
- Simple dashboard with search, sort, and pagination using DataTables.
- Clean, maintainable Laravel code with clear separation of concerns.

---

## Tech Stack

- Laravel 12.x  
- PHP 8.2  
- Redis (for caching and rate limiting)  
- Blade Templates  
- DataTables (jQuery plugin for tables)  

---

## Installation

### Prerequisites

- PHP >= 8.2  
- Composer  
- Redis server installed and running  
- Node.js and npm (for frontend asset compilation)  
- MySQL/PostgreSQL/MongoDB (optional, depending on your setup)  

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/yaser-h-m/simple-search-api.git
   cd simple-search-api

   
2. **install dependencies**
composer install

3. **prepare  .env file and setup database and redis connections** 

cp .env.example .env
php artisan key:generate


4. **run migrations and seeder**

php artisan migrate --seed

5. **run serve if you are not using vertual host**
php artisan serve


6. **run queue worker to fetch content from providers**

 run command manually 
php artisan fetch:providers:data

7. **start search**

 using Api EndPoint:
 api/search
 with queries
 /api/search?q=go&sort=score&direction=desc