-- Product STOCK OLD
-- DROP VIEW IF EXISTS product_stock;
-- CREATE VIEW product_stock AS
WITH parent_ts AS (
    SELECT product_id,
           SUM(quantity) AS parent_stock,
           AVG(avg_price) AS avg_hpp
    FROM transaction_stock
    GROUP BY product_id
),
child_agg AS (
    SELECT pc.parent_id,
           -- hitung hanya jumlah OUT (konsumsi) sebagai bilangan positif
           SUM(CASE WHEN ts.quantity < 0 THEN -ts.quantity ELSE ts.quantity END) AS child_consumed,
           GROUP_CONCAT(p.name) AS child
    FROM product_child pc
    LEFT JOIN transaction_stock ts ON pc.product_id = ts.product_id
    JOIN products p ON p.id = pc.product_id
    GROUP BY pc.parent_id
)
SELECT
    A.*,
    C.abbreviation AS unit,
    COALESCE(parent_ts.parent_stock, 0) - COALESCE(child_agg.child_consumed, 0) AS stock_available,
    COALESCE(child_agg.child_consumed, 0) AS child_avail,
    COALESCE(parent_ts.avg_hpp, 0) AS avg_hpp,
    child_agg.child,
    CASE
        WHEN COALESCE(parent_ts.parent_stock, 0) < 0 THEN 'danger'
        WHEN COALESCE(parent_ts.parent_stock, 0) = 0 THEN ''
        WHEN COALESCE(parent_ts.parent_stock, 0) <= A.limit THEN 'warning'
        ELSE 'success'
    END AS stock_status
FROM products A
LEFT JOIN product_units C ON C.id = A.product_unit
LEFT JOIN parent_ts ON parent_ts.product_id = A.id
LEFT JOIN child_agg ON child_agg.parent_id = A.id
WHERE A.is_variant IS NULL;

CREATE OR REPLACE VIEW product_stock AS
WITH parent_ts AS (
    SELECT
        ts.branch_id,
        ts.product_id,
        SUM(ts.quantity) AS parent_stock,
        AVG(ts.avg_price) AS avg_hpp
    FROM transaction_stock ts
    GROUP BY
        ts.branch_id,
        ts.product_id
),
child_agg AS (
    SELECT
        pc.parent_id,
        ts.branch_id,
        SUM(
            CASE
                WHEN ts.quantity < 0 THEN -ts.quantity
                ELSE ts.quantity
            END
        ) AS child_consumed,
        GROUP_CONCAT(p.name ORDER BY p.name SEPARATOR ', ') AS child
    FROM product_child pc
    LEFT JOIN transaction_stock ts
        ON ts.product_id = pc.product_id
    JOIN products p
        ON p.id = pc.product_id
    GROUP BY
        pc.parent_id,
        ts.branch_id
)
SELECT
    A.id,
    parent_ts.branch_id,

    A.name,
    A.sku,
    A.limit,
    A.hpp,
    A.price,
    A.tipe,
    A.category_id,
    A.product_unit,

    C.abbreviation AS unit,

    COALESCE(parent_ts.parent_stock, 0) AS parent_stock,
    COALESCE(child_agg.child_consumed, 0) AS child_consumed,

    COALESCE(parent_ts.parent_stock, 0)
        - COALESCE(child_agg.child_consumed, 0) AS stock_available,

    COALESCE(parent_ts.avg_hpp, 0) AS avg_hpp,
    child_agg.child,

    CASE
        WHEN COALESCE(parent_ts.parent_stock, 0) < 0 THEN 'danger'
        WHEN COALESCE(parent_ts.parent_stock, 0) = 0 THEN ''
        WHEN COALESCE(parent_ts.parent_stock, 0) <= A.`limit` THEN 'warning'
        ELSE 'success'
    END AS stock_status

FROM products A
LEFT JOIN parent_ts
    ON parent_ts.product_id = A.id
LEFT JOIN child_agg
    ON child_agg.parent_id = A.id
   AND child_agg.branch_id = parent_ts.branch_id
LEFT JOIN product_units C
    ON C.id = A.product_unit
WHERE A.tipe != 'parcel'
AND A.is_variant IS NULL;
-- AND LOWER(A.name) NOT LIKE '%jus%';
