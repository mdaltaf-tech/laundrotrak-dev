START TRANSACTION;

SET FOREIGN_KEY_CHECKS=0;

-- ======================================
-- CLEANUP IF PREVIOUS ATTEMPT EXISTS
-- ======================================

DROP TABLE IF EXISTS orders_new;
DROP TABLE IF EXISTS order_details_new;
DROP TABLE IF EXISTS order_addon_details_new;
DROP TABLE IF EXISTS payments_new;

DROP TEMPORARY TABLE IF EXISTS temp_numbers;
DROP TEMPORARY TABLE IF EXISTS temp_id_map;


-- ======================================
-- SAFETY BACKUP TABLES
-- ======================================

CREATE TABLE orders_backup_fix AS
SELECT * FROM orders;

CREATE TABLE order_details_backup_fix AS
SELECT * FROM order_details;

CREATE TABLE order_addon_details_backup_fix AS
SELECT * FROM order_addon_details;

CREATE TABLE payments_backup_fix AS
SELECT * FROM payments;


-- ======================================
-- GENERATE NUMBERS (1-9999)
-- ======================================

CREATE TEMPORARY TABLE temp_numbers
SELECT
a.N+b.N*10+c.N*100+d.N*1000+1 AS num
FROM
(SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a
CROSS JOIN
(SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b
CROSS JOIN
(SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) c
CROSS JOIN
(SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) d;


-- ======================================
-- INSERT MISSING ORDERS
-- ======================================

INSERT INTO orders
(
order_number,
customer_id,
customer_name,
phone_number,
order_date,
delivery_date,
sub_total,
addon_total,
discount,
tax_type,
taxable_amount,
tax_percentage,
tax_amount,
total,
note,
status,
order_type,
created_by,
financial_year_id,
created_at,
updated_at
)

SELECT
CONCAT('ORD-',LPAD(num,4,'0')),
3,
'Mohammed Arbaz',
NULL,
NOW(),
NOW(),
0,
0,
0,
NULL,
0,
0,
0,
0,
'Auto generated repair record',
0,
1,
1,
1,
NOW(),
NOW()

FROM temp_numbers t

LEFT JOIN orders o
ON o.order_number=
CONCAT('ORD-',LPAD(t.num,4,'0'))

WHERE
t.num <=
(
SELECT MAX(
CAST(REPLACE(order_number,'ORD-','') AS UNSIGNED)
)
FROM orders
)
AND o.id IS NULL;


-- ======================================
-- CREATE OLD → NEW ID MAP
-- ======================================

CREATE TEMPORARY TABLE temp_id_map
(
old_id BIGINT,
new_id BIGINT
);

SET @r:=0;

INSERT INTO temp_id_map

SELECT
id,
(@r:=@r+1)

FROM orders

ORDER BY
CAST(
REPLACE(order_number,'ORD-','')
AS UNSIGNED
);


-- ======================================
-- REBUILD TABLES
-- ======================================

CREATE TABLE orders_new LIKE orders;

INSERT INTO orders_new
SELECT *
FROM orders;

UPDATE orders_new o
JOIN temp_id_map m
ON o.id=m.old_id
SET o.id=m.new_id;



CREATE TABLE order_details_new LIKE order_details;

INSERT INTO order_details_new
SELECT *
FROM order_details;

UPDATE order_details_new od
JOIN temp_id_map m
ON od.order_id=m.old_id
SET od.order_id=m.new_id;



CREATE TABLE order_addon_details_new
LIKE order_addon_details;

INSERT INTO order_addon_details_new
SELECT *
FROM order_addon_details;

UPDATE order_addon_details_new od
JOIN temp_id_map m
ON od.order_id=m.old_id
SET od.order_id=m.new_id;



CREATE TABLE payments_new LIKE payments;

INSERT INTO payments_new
SELECT *
FROM payments;

UPDATE payments_new p
JOIN temp_id_map m
ON p.order_id=m.old_id
SET p.order_id=m.new_id;


-- ======================================
-- SWAP TABLES
-- ======================================

RENAME TABLE

orders TO orders_old_fix,
orders_new TO orders,

order_details TO order_details_old_fix,
order_details_new TO order_details,

order_addon_details TO order_addon_details_old_fix,
order_addon_details_new TO order_addon_details,

payments TO payments_old_fix,
payments_new TO payments;


-- ======================================
-- FIX AUTO_INCREMENT
-- ======================================

SET @next=
(
SELECT MAX(id)+1
FROM orders
);

SET @sql=
CONCAT(
'ALTER TABLE orders AUTO_INCREMENT=',
@next
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


SET FOREIGN_KEY_CHECKS=1;

COMMIT;

-- Verify no orphaned records All should return 0:
SELECT COUNT(*)
FROM order_details od
LEFT JOIN orders o
ON od.order_id=o.id
WHERE o.id IS NULL;

SELECT COUNT(*)
FROM order_addon_details od
LEFT JOIN orders o
ON od.order_id=o.id
WHERE o.id IS NULL;

SELECT COUNT(*)
FROM payments p
LEFT JOIN orders o
ON p.order_id=o.id
WHERE o.id IS NULL;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS order_addon_details_old_fix;
DROP TABLE IF EXISTS order_details_old_fix;
DROP TABLE IF EXISTS payments_old_fix;

DROP TABLE IF EXISTS orders_old_fix;

DROP TABLE IF EXISTS orders_backup_fix;
DROP TABLE IF EXISTS order_details_backup_fix;
DROP TABLE IF EXISTS order_addon_details_backup_fix;
DROP TABLE IF EXISTS payments_backup_fix;

SET FOREIGN_KEY_CHECKS=1;