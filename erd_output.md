# Singson Canteen — Entity Relationship Diagram

> Auto-generated on 2026-03-15 12:26:05 via `php artisan erd:generate`

```mermaid
erDiagram
    categories {
        bigint id PK
        varchar name
        text description
        varchar image
        timestamp created_at
        timestamp updated_at
    }

    inventory_logs {
        bigint id PK
        bigint menu_item_id FK
        int quantity_change
        int previous_stock
        int new_stock
        enum reason
        varchar reference_type
        bigint reference_id
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }

    order_items {
        bigint id PK
        bigint order_id FK
        bigint menu_item_id FK
        int quantity
        decimal unit_price
        decimal subtotal
        timestamp created_at
        timestamp updated_at
    }

    orders {
        bigint id PK
        varchar order_number
        bigint user_id FK
        bigint cashier_id FK
        decimal total_amount
        enum status
        enum payment_method
        text notes
        timestamp created_at
        timestamp updated_at
    }

    personal_access_tokens {
        bigint id PK
        varchar tokenable_type
        bigint tokenable_id
        text name
        varchar token
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }

    sessions {
        varchar id PK
        bigint user_id FK
        varchar ip_address
        text user_agent
        longtext payload
        int last_activity
    }

    users {
        bigint id PK
        varchar name
        varchar email
        timestamp email_verified_at
        varchar password
        varchar role
        varchar remember_token
        timestamp created_at
        timestamp updated_at
    }

    menu_items {
        bigint id PK
        bigint category_id FK
        varchar name
        text description
        decimal price
        varchar image
        int stock_quantity
        int low_stock_threshold
        tinyint is_available
        timestamp created_at
        timestamp updated_at
    }

    categories ||--o{ menu_items : "category"
    users ||--o{ orders : "user"
    users |o--o{ orders : "cashier"
    orders ||--o{ order_items : "order"
    menu_items ||--o{ order_items : "menu_item"
    menu_items ||--o{ inventory_logs : "menu_item"
    users ||--o{ inventory_logs : "created_by"
    users |o--o{ sessions : "user"
```

## How to View
- **VS Code**: Install *Markdown Preview Mermaid Support* extension, then open this file and press `Ctrl+Shift+V`
- **Online**: Paste the mermaid block into [mermaid.live](https://mermaid.live)
- **GitHub**: Just push this file — GitHub renders Mermaid in Markdown automatically ✅