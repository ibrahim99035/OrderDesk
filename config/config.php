<?php

//Database
define('DB_HOST',    'sql.freedb.tech');
define('DB_NAME',    'freedb_OrderDesk');
define('DB_USER',    'freedb_OrderDeskITI');
define('DB_PASS',    'c**?x3!GNV&tb37');

//Application
define('BASE_URL',   '/cafeteria');
define('APP_NAME',   'Cafeteria');

//File Uploads
define('UPLOAD_PATH',         __DIR__ . '/../public/uploads/');
define('PRODUCT_UPLOAD_PATH', UPLOAD_PATH . 'products/');
define('USER_UPLOAD_PATH',    UPLOAD_PATH . 'users/');

define('UPLOAD_URL',          BASE_URL . '/public/uploads/');
define('PRODUCT_UPLOAD_URL',  UPLOAD_URL . 'products/');
define('USER_UPLOAD_URL',     UPLOAD_URL . 'users/');

define('MAX_UPLOAD_SIZE',     2 * 1024 * 1024); // 2MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

//Pagination
define('ITEMS_PER_PAGE', 10);

//Order Status
define('STATUS_PROCESSING',        'processing');
define('STATUS_OUT_FOR_DELIVERY',  'out_for_delivery');
define('STATUS_DONE',              'done');
define('STATUS_CANCELLED',         'cancelled');

//Roles
define('ROLE_USER',  'user');
define('ROLE_ADMIN', 'admin');

//Error Reporting (set to 0 in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);