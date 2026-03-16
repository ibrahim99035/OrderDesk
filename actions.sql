select * from rooms;
select * from orders;

TRUNCATE TABLE order_items;
DELETE FROM TABLE orders ;
ALTER TABLE orders DROP FOREIGN KEY placed_by;

Drop TABLE order_items;
Drop TABLE orders;