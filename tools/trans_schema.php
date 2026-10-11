<?php

/**
 * Transform the OrderDesk MySQL schema into a SQLite-compatible schema
 * so the app can run without a MySQL server.
 */
$schema = file_get_contents(__DIR__ . '/../schema.sql');

// 1) Convert the `id` autoincrement columns FIRST (SQLite requires
//    `INTEGER PRIMARY KEY AUTOINCREMENT`).
$schema = preg_replace(
    '/id\s+INT\s+UNSIGNED\s+AUTO_INCREMENT\s+PRIMARY\s+KEY/m',
    'id INTEGER PRIMARY KEY AUTOINCREMENT',
    $schema
);

// 2) AUTO_INCREMENT keyword must follow INTEGER PRIMARY KEY in SQLite.
$schema = str_replace('AUTO_INCREMENT', 'AUTOINCREMENT', $schema);

// 3) INT UNSIGNED is not valid SQLite type syntax elsewhere either.
$schema = preg_replace('/\bINT\s+UNSIGNED\b/', 'INTEGER', $schema);

// 4) TINYINT(1) -> INTEGER
$schema = str_replace('TINYINT(1)', 'INTEGER', $schema);

// 5) ENUM(...) is not valid in SQLite. Replace each ENUM with a VARCHAR of
//    generous size. Values are plain strings ('user'/'admin', statuses).
$schema = preg_replace("/ENUM\\(((?:'(?:[^']|'')*)'|(?:[^'(]+))+)\\)/", 'VARCHAR(50)', $schema);

// 6) remove MySQL-only ON UPDATE CURRENT_TIMESTAMP
$schema = str_replace('ON UPDATE CURRENT_TIMESTAMP', '', $schema);

file_put_contents(__DIR__ . '/../schema.sqlite', $schema);
echo "SQLite schema written\n";

