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
		-- date,
		created_at AS date,
		`code` AS reff 
	FROM
		stock_in UNION ALL-- 	STOCK OUT
	SELECT
		product_id,
		- quantity,
		avg_price,
		-- date,
		created_at,
		`code` 
	FROM
		stock_out UNION ALL-- 	WHOLESALE
	SELECT
		product_id,
		quantity,
		price,
		-- wholesale.order_date,
		wholesale.created_at,
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
		-- date,
		created_at,
		'stock-out' 
	FROM
		stock_out_transaction UNION ALL-- 	STOCK OPNAME
	SELECT
		product_id,
		difference,
		avg_price,
		-- date,
		created_at,
		'stock-opname' 
	FROM
		stock_opname UNION ALL-- 	PRODUCTION (PRODUCT RESEP)(+)
	SELECT
		product_id,
		quantity,
		NULL,
		-- production_date,
		created_at,
		'production' 
	FROM
		production UNION ALL-- DETAIL PRODUCTION (-)
	SELECT
		production_detail.product_id,
		- production_detail.quantity,
		NULL,
		-- production.production_date,
		production.created_at,
		'production-detail' 
	FROM
		production_detail
		JOIN production ON production.id = production_detail.production_id UNION ALL-- 		DETAIL POS
	SELECT
		pos_transaction_detail.product_id,
		- pos_transaction_detail.quantity,
		pos_transaction_detail.price,
		-- pos_transaction.date,
		pos_transaction.created_at,
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

-- Product Stock
-- DROP VIEW IF EXISTS product_stock;
-- CREATE VIEW product_stock AS
SELECT
    A.*,
    C.abbreviation AS unit,
    COALESCE(SUM(B.quantity), 0) AS stock_available,
    AVG(B.avg_price) AS avg_hpp,
    CASE
        WHEN COALESCE(SUM(B.quantity), 0) < 0 THEN 'danger'
        WHEN COALESCE(SUM(B.quantity), 0) = 0 THEN ''
        WHEN COALESCE(SUM(B.quantity), 0) <= A.limit THEN 'warning'
        ELSE 'success'
    END AS stock_status
FROM
    products AS A
    LEFT JOIN transaction_stock AS B ON A.id = B.product_id
        LEFT JOIN product_units AS C ON C.id = A.product_unit
GROUP BY
    A.id, C.abbreviation;

DROP VIEW IF EXISTS product_stock;
CREATE VIEW product_stock AS
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
LEFT JOIN child_agg ON child_agg.parent_id = A.id;

-- View for Customer Tier
DROP VIEW IF EXISTS vw_customer_tier;
CREATE OR REPLACE VIEW vw_customer_tier AS
WITH customer_exp AS (
    SELECT customer_id, SUM(customer_exp) AS customer_exp 
	FROM (
				SELECT SUM(A.exp_value) AS customer_exp, B.customer_id
					FROM pos_transaction_detail AS A
					JOIN pos_transaction AS B ON A.pos_id = B.id
				WHERE B.status = 'paid'
					GROUP BY B.customer_id
				UNION
					SELECT SUM(exp), customer_id FROM deposito
					GROUP BY customer_id
				UNION
					SELECT -SUM(exp), customer_id FROM crm_point_decrement
					GROUP BY customer_id
		) AS Q
		GROUP BY Q.customer_id
),
tier_range AS (
    SELECT
        id AS tier_id,
		level AS tier_level,
        name AS tier_name,
		icon,
        exp AS min_exp,
		minimal_purchase,
		style AS tier_style,
		voucher,
		discount_transaction AS discount,
        LEAD(exp) OVER (ORDER BY `level`) AS max_exp
    FROM crm_tier
)
SELECT
    ce.customer_id,
    ce.customer_exp,
	tr.*,
	ROUND(
        IF(tr.max_exp IS NOT NULL,
            (ce.customer_exp / tr.max_exp) * 100,
            100
        ), 2
    ) AS progress_percentage
FROM customer_exp ce
JOIN tier_range tr
    ON ce.customer_exp >= tr.min_exp
    AND (ce.customer_exp < tr.max_exp OR tr.max_exp IS NULL);

-- View Report Customer
DROP VIEW IF EXISTS vw_customer_report;
CREATE OR REPLACE VIEW vw_customer_report AS
SELECT 
    c.id AS id_customer,
    c.name AS nama_customer,
    c.type AS type_customer,
    COUNT(t.id) AS jumlah_transaksi,
    SUM(t.total) AS total_transaksi,
    MAX(t.date) AS transaksi_terbaru,
    DATEDIFF(CURDATE(), MAX(t.date)) AS recency
FROM customer c
JOIN pos_transaction t ON t.customer_id = c.id
GROUP BY c.id, c.name, c.type;

-- View Deposito
DROP VIEW IF EXISTS vw_customer_deposito;
CREATE VIEW vw_customer_deposito AS
SELECT
	A.*,
	B.voucher AS nominal_using_voucher,
	B.voucher_qty AS total_used_voucher,
	( A.voucher_qty - COALESCE ( B.voucher_qty, 0 ) ) AS quantity,
	( A.deposito - COALESCE ( B.voucher, 0 ) ) AS nominal_remaining
FROM
	`deposito` AS A
	LEFT JOIN ( 
		SELECT 
			customer_id, deposito_id, SUM( voucher ) AS voucher, SUM( voucher_qty ) AS voucher_qty 
		FROM 
			pos_transaction 
		GROUP BY 
			customer_id, deposito_id 
	) AS B ON A.id = B.deposito_id;

DROP VIEW IF EXISTS vw_customer_deposito_tansaction;
CREATE VIEW vw_customer_deposito_tansaction AS
SELECT
	* 
FROM
	( 
		SELECT 
			customer_id, deposito_date, deposito, voucher_qty 
		FROM 
			deposito 
		UNION 
		SELECT 
			customer_id, date, -voucher, -voucher_qty 
		FROM 
			pos_transaction 
		WHERE voucher > 0 
	) Q
ORDER BY
	deposito_date;
