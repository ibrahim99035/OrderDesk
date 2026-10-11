#!/usr/bin/env python3
"""Transform OrderDesk MySQL schema into a SQLite-compatible schema."""
import re
import sys

SRC = "schema.sql"
DST = "schema.sqlite"

with open(SRC, "r", encoding="utf-8") as f:
    schema = f.read()

# 1) id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY  ->  id INTEGER PRIMARY KEY AUTOINCREMENT
schema = re.sub(
    r"id\s+INT\s+UNSIGNED\s+AUTO_INCREMENT\s+PRIMARY\s+KEY",
    "id INTEGER PRIMARY KEY AUTOINCREMENT",
    schema,
    flags=re.M,
)

# 2) AUTO_INCREMENT must follow INTEGER PRIMARY KEY in SQLite.
schema = schema.replace("AUTO_INCREMENT", "AUTOINCREMENT")

# 3) INT UNSIGNED is not valid SQLite type syntax.
schema = re.sub(r"\bINT\s+UNSIGNED\b", "INTEGER", schema)

# 4) TINYINT(1) -> INTEGER
schema = schema.replace("TINYINT(1)", "INTEGER")

# 5) ENUM(...) is not valid in SQLite -> VARCHAR(50).
schema = re.sub(r"ENUM\s*\([^)]*\)", "VARCHAR(50)", schema)

# 6) remove MySQL-only ON UPDATE CURRENT_TIMESTAMP.
schema = schema.replace("ON UPDATE CURRENT_TIMESTAMP", "")

with open(DST, "w", encoding="utf-8") as f:
    f.write(schema)

print("SQLite schema written to", DST)
