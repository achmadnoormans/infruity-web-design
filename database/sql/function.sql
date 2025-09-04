DROP PROCEDURE IF EXISTS get_customer_report;
CREATE PROCEDURE get_customer_report ( IN start_date DATE, IN end_date DATE ) BEGIN-- hitung total omset & hpp untuk prosentase
	WITH transaksi AS ( SELECT customer_id, SUM( total ) AS total_omset FROM pos_transaction GROUP BY customer_id ),
	hpp AS (
		SELECT
			B.customer_id,
			SUM( A.hpp ) AS hpp 
		FROM
			pos_transaction_detail A
			JOIN pos_transaction B ON A.pos_id = B.id 
		GROUP BY
			B.customer_id 
		) SELECT
		t.customer_id,
		( SELECT COUNT(*) FROM pos_transaction WHERE customer_id = t.customer_id ) AS total_transaction,
		t.total_omset,
		h.hpp,
		( t.total_omset - h.hpp ) AS profit,
		ROUND( t.total_omset * 100.0 / ( SELECT SUM( total ) FROM pos_transaction ), 2 ) AS prosentase_omset,
		ROUND( h.hpp * 100.0 / ( SELECT SUM( hpp ) FROM pos_transaction_detail JOIN pos_transaction ON pos_transaction_detail.pos_id = pos_transaction.id ), 2 ) AS prosentase_hpp,
		ROUND((
				t.total_omset - h.hpp 
				) * 100.0 / ((
				SELECT
					SUM( total ) 
				FROM
					pos_transaction 
				) - ( SELECT SUM( hpp ) FROM pos_transaction_detail JOIN pos_transaction ON pos_transaction_detail.pos_id = pos_transaction.id )),
			2 
		) AS prosentase_profit 
	FROM
		transaksi t
		JOIN hpp h ON t.customer_id = h.customer_id;
	
END;