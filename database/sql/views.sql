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
		JOIN production ON production.id = production_detail.production_id UNION ALL-- 	DETAIL PARCEL (-)
	SELECT
		production_parcel_detail.product_id,
		- production_parcel_detail.quantity,
		NULL,
		production_parcel.production_date,
		'parcel' 
	FROM
		production_parcel_detail
		JOIN production_parcel ON production_parcel.id = production_parcel_detail.production_id 
	WHERE
		production_parcel.`status` = 'complete' UNION ALL-- 	PARCEL (+)
	SELECT
		product_id,
		quantity,
		budget,
		production_date,
		'parcel' 
	FROM
		production_parcel 
	WHERE
	production_parcel.product_id IS NOT NULL 
	) AS Q;

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
    A.id, C.abbreviation


