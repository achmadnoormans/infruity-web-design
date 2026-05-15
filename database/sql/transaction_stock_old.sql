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
	AND pos_transaction.`status` IN ('paid', 'debt')
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
		transfer.created_at,
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
		transfer.created_at,
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
	AND pos_transaction.`status` IN ('paid', 'debt')
	) AS Q;
