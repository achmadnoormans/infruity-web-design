CREATE OR REPLACE VIEW product_hpp AS
WITH RECURSIVE ordered_trx AS (
    SELECT
        ROW_NUMBER() OVER (
            PARTITION BY product_id
            ORDER BY created_at
        ) AS rn,
        product_id,
        created_at,
        qty,
        harga_satuan,
        total_belanja,
        total_non_belanja,
        type,
        remarks
    FROM (
        -- =====================
        -- PENGADAAN (IN)
        -- =====================
        SELECT
            p.product_id,
            p.created_at,
            p.quantity AS qty,
            p.price AS harga_satuan,
            p.total_price AS total_belanja,
            0 AS total_non_belanja,
            '+' AS type,
            'PENGADAAN' AS remarks
        FROM wholesale_product p
        JOIN wholesale w ON p.wholesale_id = w.id

        UNION ALL

        -- =====================
        -- BARANG BUANG (OUT)
        -- =====================
        SELECT
            b.product_id,
            b.created_at,
            b.quantity * -1 AS qty,
            NULL AS harga_satuan,
            0 AS total_belanja,
            b.subtotal * -1 AS total_non_belanja,
            '-' AS type,
            'BARANG BUANG' AS remarks
        FROM sortir_transaction_detail b
        JOIN sortir_transaction s ON b.sortir_id = s.id
    ) x
),

running AS (
    -- =====================
    -- BARIS PERTAMA
    -- =====================
    SELECT
        rn,
        product_id,
        created_at,
        qty,
        harga_satuan,
        total_belanja,
        total_non_belanja,
        type,
        remarks,

        -- RAW (boleh minus)
        qty AS qty_berjalan_raw,

        -- SAFE (tidak boleh minus)
        GREATEST(qty, 0) AS qty_berjalan,

        CASE
            WHEN qty <= 0 THEN 0
            ELSE total_belanja + total_non_belanja
        END AS total_aset_berjalan
    FROM ordered_trx
    WHERE rn = 1

    UNION ALL

    -- =====================
    -- BARIS SELANJUTNYA
    -- =====================
    SELECT
        t.rn,
        t.product_id,
        t.created_at,
        t.qty,
        t.harga_satuan,
        t.total_belanja,
        t.total_non_belanja,
        t.type,
        t.remarks,

        -- RAW (akumulasi murni)
        r.qty_berjalan_raw + t.qty AS qty_berjalan_raw,

        -- SAFE (stok bisnis rule)
        GREATEST(r.qty_berjalan + t.qty, 0) AS qty_berjalan,

        -- aset ikut reset kalau stok habis
        CASE
            WHEN r.qty_berjalan + t.qty <= 0 THEN 0
            ELSE r.total_aset_berjalan
                 + t.total_belanja
                 + t.total_non_belanja
        END AS total_aset_berjalan
    FROM running r
    JOIN ordered_trx t
      ON t.product_id = r.product_id
     AND t.rn = r.rn + 1
)

SELECT
    product_id,
    type,
    remarks,
    ABS(qty) AS qty,

    -- dua versi stok
    qty_berjalan_raw,
    qty_berjalan,

    harga_satuan,
    total_belanja,
    total_non_belanja,

    -- HPP berjalan (dibulatkan ke atas)
    CASE
        WHEN qty_berjalan = 0 THEN 0
        ELSE CEILING(total_aset_berjalan / qty_berjalan)
    END AS hpp_berjalan,

    total_aset_berjalan,

    qty_berjalan *
    CASE
        WHEN qty_berjalan = 0 THEN 0
        ELSE CEILING(total_aset_berjalan / qty_berjalan)
    END AS qty_x_hpp,

    created_at
FROM running
ORDER BY product_id, rn;

CREATE OR REPLACE VIEW product_hpp_branch AS
WITH RECURSIVE ordered_trx AS (
    SELECT
        ROW_NUMBER() OVER (
            PARTITION BY branch_id, product_id
            ORDER BY created_at
        ) AS rn,
        branch_id,
        product_id,
        created_at,
        qty,
        harga_satuan,
        total_belanja,
        total_non_belanja,
        type,
        remarks
    FROM (
        -- =====================
        -- PENGADAAN (IN)
        -- =====================
        SELECT
            w.branch_id,
            p.product_id,
            p.created_at,
            p.quantity AS qty,
            p.price AS harga_satuan,
            p.total_price AS total_belanja,
            0 AS total_non_belanja,
            '+' AS type,
            'PENGADAAN' AS remarks
        FROM wholesale_product p
		JOIN wholesale AS w ON p.wholesale_id = w.id

        UNION ALL

        -- =====================
        -- BARANG BUANG (OUT)
        -- =====================
        SELECT
            s.branch_id,
            b.product_id,
            b.created_at,
            b.quantity * -1 AS qty,
            NULL AS harga_satuan,
            0 AS total_belanja,
            b.subtotal * -1 AS total_non_belanja,
            '-' AS type,
            'BARANG BUANG' AS remarks
        FROM sortir_transaction_detail b
		JOIN sortir_transaction AS s ON b.sortir_id = s.id

    ) x
),

running AS (
    -- =====================
    -- BARIS PERTAMA
    -- =====================
    SELECT
        rn,
        branch_id,
        product_id,
        created_at,
        qty,
        harga_satuan,
        total_belanja,
        total_non_belanja,
        type,
        remarks,

        -- RAW (boleh minus)
        qty AS qty_berjalan_raw,

        -- SAFE (tidak boleh minus)
        GREATEST(qty, 0) AS qty_berjalan,

        CASE
            WHEN qty <= 0 THEN 0
            ELSE total_belanja + total_non_belanja
        END AS total_aset_berjalan

    FROM ordered_trx
    WHERE rn = 1

    UNION ALL

    -- =====================
    -- BARIS SELANJUTNYA
    -- =====================
    SELECT
        t.rn,
        t.branch_id,
        t.product_id,
        t.created_at,
        t.qty,
        t.harga_satuan,
        t.total_belanja,
        t.total_non_belanja,
        t.type,
        t.remarks,

        -- RAW (akumulasi murni)
        r.qty_berjalan_raw + t.qty AS qty_berjalan_raw,

        -- SAFE (stok bisnis rule)
        GREATEST(r.qty_berjalan + t.qty, 0) AS qty_berjalan,

        -- aset ikut reset kalau stok habis
        CASE
            WHEN r.qty_berjalan + t.qty <= 0 THEN 0
            ELSE r.total_aset_berjalan
                 + t.total_belanja
                 + t.total_non_belanja
        END AS total_aset_berjalan

    FROM running r
    JOIN ordered_trx t
      ON t.branch_id  = r.branch_id
     AND t.product_id = r.product_id
     AND t.rn = r.rn + 1
)

SELECT
    branch_id,
    product_id,
    type,
    remarks,
    ABS(qty) AS qty,

    -- dua versi stok
    qty_berjalan_raw,
    qty_berjalan,

    harga_satuan,
    total_belanja,
    total_non_belanja,

    -- HPP berjalan (dibulatkan ke atas)
    CASE
        WHEN qty_berjalan = 0 THEN 0
        ELSE CEILING(total_aset_berjalan / qty_berjalan)
    END AS hpp_berjalan,

    total_aset_berjalan,

    qty_berjalan *
    CASE
        WHEN qty_berjalan = 0 THEN 0
        ELSE CEILING(total_aset_berjalan / qty_berjalan)
    END AS qty_x_hpp,

    created_at

FROM running
ORDER BY branch_id, product_id, rn;
