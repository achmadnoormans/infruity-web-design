DROP VIEW IF EXISTS view_wholesale;
CREATE VIEW view_wholesale AS
SELECT
    wholesale.id AS id,
    wholesale.branch_id AS branch_id,
    wholesale.order_number AS order_number,
    wholesale.status AS status,
    wholesale.order_date,
    wholesale.created_at,
    COUNT(wholesale_product.id) AS total_product,
	users.nm_user AS created_by
FROM wholesale
JOIN wholesale_product ON wholesale_product.wholesale_id = wholesale.id
LEFT JOIN users ON users.id_user = wholesale.created_by
GROUP BY
    wholesale.id,
    wholesale.status,
    wholesale.order_date,
    wholesale.created_at;

DROP VIEW IF EXISTS transaction_stock;
CREATE VIEW transaction_stock AS
SELECT
	*
FROM
	(-- 	STOCK IN
	SELECT
		0 AS branch_id,
		product_id,
		quantity,
		avg_price,
		-- date,
		created_at AS date,
		`code` AS reff,
		'stock-in' AS url,
		id AS id
	FROM
		stock_in
	UNION ALL

	-- STOCK OUT
	SELECT
		0 AS branch_id,
		product_id,
		- quantity,
		avg_price,
		-- date,
		created_at,
		`code`,
		'stock-out',
		id
	FROM
		stock_out
	UNION ALL

	-- WHOLESALE
	SELECT
		branch_id,
		product_id,
		quantity,
		price,
		-- wholesale.order_date,
		wholesale.created_at,
		'pengadaan' AS reff,
		'wholesale' AS url,
		wholesale.id AS id
	FROM
		wholesale_product
		JOIN wholesale ON wholesale_product.wholesale_id = wholesale.id
	WHERE
		wholesale.`status` = 'posting'
		AND product_id != 0
	UNION ALL

	-- STOCK OUT TRANSACTION
	SELECT
		0 AS branch_id,
		product_id,
		- quantity,
		avg_price,
		-- date,
		created_at,
		'stock-out',
		'stock-out-transaction',
		id
	FROM
		stock_out_transaction
	UNION ALL

	-- STOCK OPNAME
	SELECT
		branch_id AS branch_id,
		product_id,
		difference,
		avg_price,
		-- date,
		created_at,
		'stock-opname',
		'stock-opname',
		id
	FROM
		stock_opname
	UNION ALL

	-- PRODUCTION (PRODUCT RESEP)(+)
	SELECT
		branch_id,
		product_id,
		quantity,
		NULL,
		-- production_date,
		created_at,
		'produksi',
		'production',
		id
	FROM
		production
        WHERE production.`status` IN ('posting', 'complete')
	UNION ALL

	-- DETAIL PRODUCTION (-)
	SELECT
		production.branch_id,
		production_detail.product_id,
		- production_detail.quantity,
		NULL,
		-- production.production_date,
		production.created_at,
		'produksi-detail',
		'production',
		production.id
	FROM
		production_detail
		JOIN production ON production.id = production_detail.production_id
        WHERE production.`status` IN ('posting', 'complete')
        UNION ALL

	-- DETAIL POS
	SELECT
		pos_transaction.branch_id,
		pos_transaction_detail.product_id,
		- pos_transaction_detail.quantity,
		pos_transaction_detail.price,
		-- pos_transaction.date,
		pos_transaction.created_at,
		'pos',
		'pos',
		 pos_transaction.id AS id
	FROM
		pos_transaction_detail
	JOIN pos_transaction ON pos_transaction.id = pos_transaction_detail.pos_id
    WHERE pos_transaction_detail.deleted_at IS NULL
	AND pos_transaction.`status` != 'draft'
    AND pos_transaction.deleted_at IS NULL
	UNION ALL

    -- DETAIL SORTIR
	SELECT
		sortir_transaction.branch_id,
		sortir_transaction_detail.product_id,
		- sortir_transaction_detail.quantity,
		sortir_transaction_detail.price,
		sortir_transaction_detail.created_at,
		'sortir',
		'sortir',
		sortir_transaction.id AS id
	FROM
	sortir_transaction_detail
	JOIN sortir_transaction ON sortir_transaction_detail.sortir_id = sortir_transaction.id
    UNION ALL

	-- TRANSFER (ASAL)
	SELECT
		transfer.branch_id,
		transfer_detail.product_id,
		- transfer_detail.quantity,
		transfer_detail.price,
		transfer.date,
		'transfer asal',
		'transfer',
		transfer.id AS id
	FROM transfer
	JOIN transfer_detail ON transfer_detail.transfer_id = transfer.id
	WHERE transfer.`status` = 'selesai'
	UNION ALL

	-- TRANSFER (TUJUAN)
	SELECT
		transfer.branch_destination_id,
		transfer_detail.product_id,
		transfer_detail.quantity,
		transfer_detail.price,
		transfer.date,
		'transfer tujuan',
		'transfer',
		transfer.id AS id
	FROM transfer
	JOIN transfer_detail ON transfer_detail.transfer_id = transfer.id
	WHERE transfer.`status` = 'selesai'
	UNION ALL

	SELECT
		pos_transaction.branch_id,
		production_parcel_detail.product_id,
		- production_parcel_detail.quantity,
		production_parcel_detail.price,
		pos_transaction.created_at,
		'pos-parcel',
		'pos',
		pos_transaction.id AS id
	FROM production_parcel_detail
	JOIN pos_transaction ON pos_transaction.id = production_parcel_detail.pos_id
	WHERE pos_transaction.`status` != 'draft'
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
	WHERE A.is_variant IS NULL
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

-- View for Customer Tier
DROP VIEW IF EXISTS vw_customer_tier;
CREATE OR REPLACE VIEW vw_customer_tier AS
WITH customer_exp AS (
    SELECT customer_id, SUM(customer_exp) AS customer_exp
	FROM (
				SELECT SUM(A.exp_value) AS customer_exp, B.customer_id
					FROM pos_transaction_detail AS A
					JOIN pos_transaction AS B ON A.pos_id = B.id
				WHERE B.status = 'paid' AND A.deleted_at IS NULL
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
WHERE t.deleted_at IS NULL
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
