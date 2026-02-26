DROP VIEW IF EXISTS vw_customer_transaction;
CREATE VIEW vw_customer_transaction AS
SELECT
	A.id AS pos_id,
	A.created_at,
	A.customer_id,
	COALESCE(A.total,0) AS total,
	COALESCE(B.total,0) AS total_detail,
	COALESCE(B.total_hpp,0) AS hpp,
	(COALESCE(A.total,0) - COALESCE(B.total_hpp,0)) AS profit,
	C.name,
	C.birth_of_date,
	C.gender,
	TIMESTAMPDIFF(YEAR, C.birth_of_date,	CURDATE()) AS age,
	A.branch_id,
	E.name AS branch_name,
	C.city,
	C.district,
	C.village
FROM
	pos_transaction AS A
	LEFT JOIN (
	    SELECT
            pos_id, SUM( subtotal ) AS total, SUM( subtotal_hpp ) AS total_hpp
        FROM
            pos_transaction_detail
            WHERE deleted_at IS NULL
        GROUP BY pos_id
	) AS B ON B.pos_id = A.id
	LEFT JOIN customer AS C ON A.customer_id = C.id
	LEFT JOIN branch AS E ON A.branch_id = E.id
    WHERE A.deleted_at IS NULL
	ORDER BY A.created_at DESC;

DROP VIEW IF EXISTS vw_product_buang;
CREATE VIEW vw_product_buang AS
SELECT
	A.product_id AS id,
	B.name,
	C.id AS unit_id,
	C.abbreviation AS satuan,
	SUM( A.quantity ) AS quantity,
	AVG( A.avg_price ) AS hpp,
	(
	SUM( A.quantity ) * AVG( A.avg_price )) AS total_hpp
FROM
	stock_out AS A
	JOIN products AS B ON A.product_id = B.id
	JOIN product_units AS C ON B.product_unit = C.id
GROUP BY
	A.product_id
ORDER BY
	SUM( A.quantity ) DESC