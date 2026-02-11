CREATE OR REPLACE VIEW product_hpp AS
WITH RECURSIVE ordered_trx AS (
    SELECT
        ROW_NUMBER() OVER (
            PARTITION BY product_id
            ORDER BY created_at, type DESC
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
        SELECT
            COALESCE(pc.parent_id, p.product_id) AS product_id,
            p.created_at,
            p.quantity AS qty,
            p.price AS harga_satuan,
            CASE
                WHEN p.total_price IS NULL OR p.total_price = 0
                THEN p.quantity * p.price
                ELSE p.total_price
            END AS total_belanja,
            0 AS total_non_belanja,
            '+' AS type,
            'PENGADAAN' AS remarks
        FROM wholesale_product p
        LEFT JOIN product_child pc
            ON pc.product_id = p.product_id

        UNION ALL

        SELECT
            COALESCE(pc.parent_id, b.product_id),
            b.created_at,
            b.quantity * -1,
            NULL,
            0,
            0, -- kita nolkan dulu, akan dihitung ulang
            '-',
            'BARANG BUANG'
        FROM sortir_transaction_detail b
        LEFT JOIN product_child pc
            ON pc.product_id = b.product_id

        UNION ALL

        SELECT
            COALESCE(pc.parent_id, b.product_id),
            b.created_at,
            b.quantity * -1,
            NULL,
            0,
            0, -- kita nolkan dulu
            '-',
            'PENJUALAN'
        FROM pos_transaction_detail b
        JOIN pos_transaction pos ON b.pos_id = pos.id
        LEFT JOIN product_child pc
            ON pc.product_id = b.product_id
        WHERE pos.deleted_at IS NULL
    ) x
),

running AS (

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

        qty AS qty_berjalan_raw,
        GREATEST(qty,0) AS qty_berjalan,

        CASE
            WHEN qty <= 0 THEN 0
            ELSE total_belanja
        END AS total_aset_berjalan

    FROM ordered_trx
    WHERE rn = 1

    UNION ALL

    SELECT
        t.rn,
        t.product_id,
        t.created_at,
        t.qty,
        t.harga_satuan,
        t.total_belanja,

        -- 🔥 HITUNG ULANG total_non_belanja UNTUK OUT
        CASE
            WHEN t.qty < 0 AND r.qty_berjalan > 0 THEN
                - (
                    LEAST(ABS(t.qty), r.qty_berjalan)
                    * (r.total_aset_berjalan / NULLIF(r.qty_berjalan,0))
                  )
            ELSE 0
        END AS total_non_belanja,

        t.type,
        t.remarks,

        r.qty_berjalan_raw + t.qty,
        GREATEST(r.qty_berjalan + t.qty,0),

        CASE
            WHEN r.qty_berjalan = 0 AND t.qty > 0 THEN
                t.total_belanja

            WHEN t.qty > 0 THEN
                r.total_aset_berjalan + t.total_belanja

            WHEN r.qty_berjalan > 0 THEN
                r.total_aset_berjalan
                - (
                    LEAST(ABS(t.qty), r.qty_berjalan)
                    * (r.total_aset_berjalan / NULLIF(r.qty_berjalan,0))
                  )

            ELSE 0
        END

    FROM running r
    JOIN ordered_trx t
      ON t.product_id = r.product_id
     AND t.rn = r.rn + 1
),

final AS (
    SELECT *,
        CASE
            WHEN qty_berjalan = 0 THEN NULL
            ELSE total_aset_berjalan / qty_berjalan
        END AS hpp_real
    FROM running
)

SELECT
    product_id,
    type,
    remarks,
    ABS(qty) AS qty,
    qty_berjalan_raw,
    qty_berjalan,
    harga_satuan,
    total_belanja,
    total_non_belanja,

    COALESCE(
        hpp_real,
        MAX(hpp_real) OVER (
            PARTITION BY product_id
            ORDER BY rn
            ROWS BETWEEN CURRENT ROW AND UNBOUNDED FOLLOWING
        )
    ) AS hpp_berjalan,

    total_aset_berjalan,
    created_at

FROM final
ORDER BY product_id, rn;


-- =====================
-- VIEW PRODUCT HPP
-- =====================

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
