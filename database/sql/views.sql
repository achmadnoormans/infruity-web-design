DROP VIEW IF EXISTS view_wholesale;
CREATE VIEW view_wholesale AS
SELECT 
    wholesale.id AS id,
    wholesale.order_number AS order_number,
    wholesale.status AS status,
    wholesale.order_date,
    COUNT(wholesale_product.id) AS total_product
FROM wholesale
JOIN wholesale_product ON wholesale_product.wholesale_id = wholesale.id
GROUP BY 
    wholesale.id, 
    wholesale.status,
    wholesale.order_date;

DROP VIEW IF EXISTS transaction_stock;
CREATE VIEW transaction_stock AS
SELECT
	* 
FROM
	(-- 	STOCK IN
	SELECT
		product_id,
		quantity,
		avg_price,
		date,
		`code` AS reff 
	FROM
		stock_in UNION ALL-- 	STOCK OUT
	SELECT
		product_id,
		- quantity,
		avg_price,
		date,
		`code` 
	FROM
		stock_out UNION ALL-- 	WHOLESALE
	SELECT
		product_id,
		quantity,
		price,
		wholesale.order_date,
		'wholelsale' AS reff 
	FROM
		wholesale_product
		JOIN wholesale ON wholesale_product.wholesale_id = wholesale.id 
	WHERE
		wholesale.`status` = 'posting' 
		AND product_id != 0 UNION ALL-- 	STOCK OUT TRANSACTION
	SELECT
		product_id,
		- quantity,
		avg_price,
		date,
		'stock-out' 
	FROM
		stock_out_transaction UNION ALL-- 	STOCK OPNAME
	SELECT
		product_id,
		difference,
		avg_price,
		date,
		'stock-opname' 
	FROM
		stock_opname UNION ALL-- 	PRODUCTION (PRODUCT RESEP)(+)
	SELECT
		product_id,
		quantity,
		NULL,
		production_date,
		'production' 
	FROM
		production UNION ALL-- DETAIL PRODUCTION (-)
	SELECT
		production_detail.product_id,
		- production_detail.quantity,
		NULL,
		production.production_date,
		'production-detail' 
	FROM
		production_detail
		JOIN production ON production.id = production_detail.production_id UNION ALL-- 		DETAIL POS
	SELECT
		pos_transaction_detail.product_id,
		- pos_transaction_detail.quantity,
		pos_transaction_detail.price,
		pos_transaction.date,
		'pos' 
	FROM
		pos_transaction_detail
	JOIN pos_transaction ON pos_transaction.id = pos_transaction_detail.pos_id 
	) AS Q;

-- Sortir view
DROP VIEW IF EXISTS sortir_view;
CREATE VIEW sortir_view AS
SELECT
    A.*,
    B.product_id,
    SUM( B.quantity ) AS stock_available,
    AVG( B.avg_price ) AS avg_hpp,
    C.abbreviation AS satuan
FROM
    products AS A
    LEFT JOIN transaction_stock AS B ON A.id = B.product_id
    LEFT JOIN product_units AS C ON A.product_unit = C.id
GROUP BY
    A.id, B.product_id, C.abbreviation
ORDER BY 
    stock_available DESC;

DROP VIEW IF EXISTS product_stock;
CREATE VIEW product_stock AS
SELECT
    A.*,
    C.abbreviation AS unit,
    COALESCE(SUM(B.quantity), 0) AS stock_available,
    AVG(B.avg_price) AS avg_hpp,
    CASE
        WHEN COALESCE(SUM(B.quantity), 0) = 0 THEN 'danger'
        WHEN COALESCE(SUM(B.quantity), 0) <= A.limit THEN 'warning'
        ELSE 'success'
    END AS stock_status
FROM
    products AS A
    LEFT JOIN transaction_stock AS B ON A.id = B.product_id
        LEFT JOIN product_units AS C ON C.id = A.product_unit
GROUP BY
    A.id, C.abbreviation;

-- View for Customer Tier
DROP VIEW IF EXISTS vw_customer_tier;
CREATE OR REPLACE VIEW vw_customer_tier AS
WITH customer_exp AS (
    SELECT SUM(A.exp_value) AS customer_exp, B.customer_id
    FROM pos_transaction_detail AS A
    JOIN pos_transaction AS B ON A.pos_id = B.id
	WHERE B.status = 'paid'
    GROUP BY B.customer_id
),
tier_range AS (
    SELECT
        id,
        name AS tier_name,
        exp AS min_exp,
        LEAD(exp) OVER (ORDER BY `level`) AS max_exp
    FROM crm_tier
)
SELECT
    ce.customer_id,
    tr.tier_name,
    ce.customer_exp,
    tr.min_exp,
    tr.max_exp
FROM customer_exp ce
JOIN tier_range tr
    ON ce.customer_exp >= tr.min_exp
    AND (ce.customer_exp < tr.max_exp OR tr.max_exp IS NULL);


