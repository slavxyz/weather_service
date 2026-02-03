# Weather API (Symfony)

This is a simple REST API built with Symfony that provides weather information for a given city.

## Clone the repository:

```bash
git clone <repository>
cd weather_service
```
## Start the project using Docker:

### Start service
docker compose up -d --build

### Run migrations after 20 seconds  
docker compose exec apache php bin/console doctrine:migrations:migrate

### Load fixtures
docker compose exec apache php bin/console doctrine:fixtures:load

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

Edit .env
Uncomment following line: 
#DATABASE_URL="mysql://app:123456@127.0.0.1:3306/weather_db?serverVersion=8.0&sslMode=DISABLED"

and comment:  
DATABASE_URL="mysql://app:123456@db:3306/weather_db?serverVersion=8.0&sslMode=DISABLED"

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
