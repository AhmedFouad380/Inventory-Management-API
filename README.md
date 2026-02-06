# Inventory Management API

A simplified RESTful API for managing inventory across multiple warehouses.

## Features
- **Multi-Warehouse Management**: Track stock across different locations.
- **Stock Transfers**: Efficiently move items between warehouses with transactional integrity.
- **Search & Filtering**: Search products by name, SKU, or price range.
- **Caching**: Optimized warehouse inventory retrieval using Laravel Cache.
- **Low Stock Alerts**: Automatic event triggers when stock levels drop below a threshold (10 units).
- **Security**: Sanctum-based authentication and granular authorization policies.

## Installation

1. Clone the repository.
2. Run `composer install`.
3. Set up your `.env` (Default uses SQLite).
4. Run migrations: `php artisan migrate`.
5. Start the server: `php artisan serve`.

## API Documentation

### Authentication
Most endpoints require a Bearer Token. Use Laravel Sanctum to generate tokens.

### Endpoints

#### 1. List Inventory Items
- **URL:** `GET /api/inventory`
- **Params:** `search`, `min_price`, `max_price`, `limit`, `page`
- **Description:** Paginated list of all products.

#### 2. Get Warehouse Inventory
- **URL:** `GET /api/warehouses/{id}/inventory`
- **Description:** Get all items in a specific warehouse. Results are cached for 1 hour.

#### 3. Transfer Stock
- **URL:** `POST /api/stock-transfers`
- **Auth:** Required
- **Body:**
  ```json
  {
    "from_warehouse_id": 1,
    "to_warehouse_id": 2,
    "inventory_item_id": 5,
    "quantity": 10
  }
  ```

## Testing
Run the automated test suite:
```bash
php artisan test
```
The suite includes unit tests for transfer logic, feature tests for the API, and event tests for low stock detection.
