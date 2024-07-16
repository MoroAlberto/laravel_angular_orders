# Laravel API

This Laravel API project provides endpoints for managing orders and products.

## Setup

### Using Docker

For detailed instructions on setting up Docker, please refer to the separate Docker README file included in this
repository

## Routes

### Orders

- **GET /api/orders** - Retrieve all orders
- **GET /api/orders/{order}** - Retrieve a specific order
- **POST /api/orders** - Create a new order
- **PUT /api/orders/{order}** - Update an existing order
- **DELETE /api/orders/{order}** - Delete an order

### Products

- **GET /api/products** - Retrieve all products
- **GET /api/products/{product}** - Retrieve a specific product
- **POST /api/products** - Create a new product
- **PUT /api/products/{product}** - Update an existing product
- **DELETE /api/products/{product}** - Delete a product

## Testing

The project includes PHPUnit tests for API endpoints and services. Run the tests with the following command:

```bash
docker exec -it local_backend vendor/bin/phpunit
```

OR

```bash
docker exec -it local_backend /bin/bash
php artisan test
```

## Frontend

This repository contains the backend API only. For the frontend implementation using Angular, please refer to the
separate Angular repository or documentation.
