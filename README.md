# Weather API (Symfony)

This is a simple REST API built with Symfony that provides weather information for a given city.

## Clone the repository:

```bash
git clone <repository>
cd weather_service
```
## Start the project using Docker:

docker compose up -d --build

### Open the following link in your browser or in Postman to test the API:

 - http://localhost:8080/api/v1/cities/London/weather

### Example endpoint:
    GET /api/v1/cities/{city}/weather


### Example:
    GET /api/v1/cities/London/weather

## Start the project without Docker command line:

### install dependencies

```
composer install
```

### Configure database

1. Edit .env
DATABASE_URL="mysql://app:123456@127.0.0.1:3306/<weather_db>?serverVersion=8.0&sslMode=DISABLED"

2. CREATE DATABASE <weather_db>

### Run migrations 

```
php bin/console doctrine:migrations:migrate
```

### Load fixtures
```
php bin/console doctrine:fixtures:load --no-interaction
```

### Start symfony server
```
symfomy serve
```


### Open in browser
http://localhost:8000

### Use curl
    curl http://localhost:8080/api/v1/cities/{city}/weather

Example:
    curl http://localhost:8080/api/v1/cities/London/weather










