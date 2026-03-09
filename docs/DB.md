# ERD Textual Description — Cafeteria Database

---

## Entities & Their Attributes

### ROOM
- **PK** room_id
- room_number

```sql
CREATE TABLE rooms (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL UNIQUE
);
```

---

### USER
- **PK** user_id
- name
- email *(unique)*
- password
- role *(admin / user)*
- **FK** room_id → ROOM
- extension
- image
- is_active
- created_at

```sql
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)         NOT NULL,
    email       VARCHAR(150)         NOT NULL UNIQUE,
    password    VARCHAR(255)         NOT NULL,
    role        ENUM('user','admin') NOT NULL DEFAULT 'user',
    room_id     INT UNSIGNED,
    extension   VARCHAR(20),
    image       VARCHAR(255),
    is_active  BOOLEAN               NOT NULL DEFAULT TRUE,
    created_at  DATETIME             NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (room_id) REFERENCES rooms(id)
);
```

---

### CATEGORY
- **PK** category_id
- name *(unique)*

```sql
CREATE TABLE categories (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL UNIQUE
);
```

---

### PRODUCT
- **PK** product_id
- name
- price
- **FK** category_id → CATEGORY
- image
- is_available
- created_at

```sql
CREATE TABLE products (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    price         DECIMAL(10,2) NOT NULL,
    category_id   INT UNSIGNED,
    image         VARCHAR(255),
    is_available  TINYINT(1)    NOT NULL DEFAULT 1,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

---

### ORDER
- **PK** order_id
- **FK** user_id → USER *(who the order is for)*
- **FK** placed_by → USER *(who placed it)*
- **FK** room_id → ROOM *(delivery destination)*
- notes
- status *(processing / out_for_delivery / done / cancelled)*
- total
- created_at
- updated_at

```sql
CREATE TABLE orders (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    placed_by   INT UNSIGNED    NOT NULL,
    room_id     INT UNSIGNED    NOT NULL,
    notes       TEXT,
    status      ENUM('processing','out_for_delivery','done','cancelled')
                                NOT NULL DEFAULT 'processing',
    total       DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)   REFERENCES users(id),
    FOREIGN KEY (placed_by) REFERENCES users(id),
    FOREIGN KEY (room_id)   REFERENCES rooms(id)
);
```

---

### ORDER_ITEM
- **PK** order_item_id
- **FK** order_id → ORDER
- **FK** product_id → PRODUCT
- quantity
- unit_price *(snapshot at time of order)*

```sql
CREATE TABLE order_items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED   NOT NULL,
    product_id  INT UNSIGNED   NOT NULL,
    quantity    INT UNSIGNED   NOT NULL DEFAULT 1,
    unit_price  DECIMAL(10,2)  NOT NULL,

    FOREIGN KEY (order_id)   REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## Relationships

### ROOM — USER
```
ROOM ||——o{ USER
One room can have zero or many users.
One user belongs to exactly one room (or none — nullable).
```

### ROOM — ORDER
```
ROOM ||——o{ ORDER
One room can receive zero or many orders.
One order is delivered to exactly one room.
```

### USER — ORDER (as recipient)
```
USER ||——o{ ORDER
One user can have zero or many orders placed for them.
One order belongs to exactly one user.
```

### USER — ORDER (as placer)
```
USER ||——o{ ORDER
One user (or admin) can place zero or many orders.
One order is placed by exactly one person.
```
> These are two distinct relationships on the same two entities, represented by `user_id` and `placed_by` — both foreign keys point back to USER.

### CATEGORY — PRODUCT
```
CATEGORY ||——o{ PRODUCT
One category can have zero or many products.
One product belongs to zero or one category (nullable — SET NULL on delete).
```

### ORDER — ORDER_ITEM
```
ORDER ||——|{ ORDER_ITEM
One order must have one or many order items.
One order item belongs to exactly one order.
Deletion cascades — if order is deleted, its items are deleted too.
```

### PRODUCT — ORDER_ITEM
```
PRODUCT ||——o{ ORDER_ITEM
One product can appear in zero or many order items.
One order item references exactly one product.
No cascade — product deletion does not remove historical order items.
```

---

## Cardinality Summary Table

| Relationship | Left | Cardinality | Right |
|---|---|---|---|
| ROOM → USER | One room | has zero or many | users |
| USER → ROOM | One user | belongs to zero or one | room |
| ROOM → ORDER | One room | receives zero or many | orders |
| ORDER → ROOM | One order | delivers to exactly one | room |
| USER → ORDER | One user | has zero or many | orders (as recipient) |
| USER → ORDER | One user | places zero or many | orders (as placer) |
| CATEGORY → PRODUCT | One category | has zero or many | products |
| PRODUCT → CATEGORY | One product | belongs to zero or one | category |
| ORDER → ORDER_ITEM | One order | contains one or many | order items |
| ORDER_ITEM → ORDER | One item | belongs to exactly one | order |
| PRODUCT → ORDER_ITEM | One product | appears in zero or many | order items |
| ORDER_ITEM → PRODUCT | One item | references exactly one | product |

---

## Notable Design Points Visible in the ERD

| Point | Explanation |
|---|---|
| USER has two FK relationships to ORDER | Separates who ordered from who placed it |
| ORDER_ITEM has its own `unit_price` | Breaks dependency on PRODUCT.price — historical accuracy |
| ROOM is a standalone entity | Shared across USER and ORDER independently |
| CATEGORY uses SET NULL | Products survive category deletion |
| ORDER_ITEM cascades from ORDER | No orphaned line items |
| PRODUCT has no cascade | Order history survives product deactivation |