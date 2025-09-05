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
	D.branch_id,
	E.name AS branch_name,
	C.city,
	kabupaten.name AS city_name,
	C.district,
	kecamatan.name AS district_name,
	C.village,
	kelurahan.name AS village_name
FROM
	pos_transaction AS A
	LEFT JOIN ( 
	    SELECT 
            pos_id, SUM( subtotal ) AS total, SUM( hpp ) AS total_hpp 
        FROM 
            pos_transaction_detail 
        GROUP BY pos_id 
	) AS B ON B.pos_id = A.id
	LEFT JOIN customer AS C ON A.customer_id = C.id
	LEFT JOIN pos_payment AS D ON A.id = D.pos_id
	LEFT JOIN branch AS E ON D.branch_id = E.id
	LEFT JOIN reg_regencies AS kabupaten ON kabupaten.id = C.city
	LEFT JOIN reg_districts AS kecamatan ON kecamatan.id = C.district
	LEFT JOIN reg_villages AS kelurahan ON kelurahan.id = C.village