CREATE TABLE rooms (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) NOT NULL UNIQUE
);

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

CREATE TABLE categories (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE products (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    description          VARCHAR(100)  NOT NULL,

    price         DECIMAL(10,2) NOT NULL,
    category_id   INT UNSIGNED,
    image         VARCHAR(255),
    is_available  TINYINT(1)    NOT NULL DEFAULT 1,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

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

CREATE TABLE order_items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED   NOT NULL,
    product_id  INT UNSIGNED   NOT NULL,
    quantity    INT UNSIGNED   NOT NULL DEFAULT 1,
    unit_price  DECIMAL(10,2)  NOT NULL,

    FOREIGN KEY (order_id)   REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);