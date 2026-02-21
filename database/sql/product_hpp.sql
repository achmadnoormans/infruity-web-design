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
        -- ================= PENGADAAN =================
        SELECT
            COALESCE(pc.parent_id, p.product_id) AS product_id,
            p.created_at AS created_at,
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
        LEFT JOIN product_child pc ON pc.product_id = p.product_id

        UNION ALL

        -- ================= BARANG BUANG =================
        SELECT
            COALESCE(pc.parent_id, b.product_id) AS product_id,
            b.created_at,
            b.quantity * -1,
            NULL,
            0,
            0,
            '-',
            'BARANG BUANG'
        FROM sortir_transaction_detail b
        LEFT JOIN product_child pc ON pc.product_id = b.product_id

        UNION ALL

        -- ================= PENJUALAN =================
        SELECT
            COALESCE(pc.parent_id, b.product_id),
            b.created_at,
            b.quantity * -1,
            NULL,
            0,
            0,
            '-',
            'PENJUALAN'
        FROM pos_transaction_detail b
        JOIN pos_transaction pos ON b.pos_id = pos.id
        LEFT JOIN product_child pc ON pc.product_id = b.product_id
        WHERE pos.deleted_at IS NULL

        UNION ALL

        -- ================= OPNAME =================
        SELECT
            COALESCE(pc.parent_id, p.product_id),
            p.created_at,
            p.difference,
            p.avg_price,
            0,
            0,
            '~',
            'OPNAME'
        FROM stock_opname p
        LEFT JOIN product_child pc ON pc.product_id = p.product_id
    ) x
),

running AS (

    -- BARIS PERTAMA
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
        qty AS qty_berjalan,
        CASE WHEN qty > 0 THEN total_belanja ELSE 0 END AS total_aset_berjalan
    FROM ordered_trx
    WHERE rn = 1

    UNION ALL

    -- BARIS SELANJUTNYA
    SELECT
        t.rn,
        t.product_id,
        t.created_at,
        t.qty,
        t.harga_satuan,
        t.total_belanja,

        -- COGS
        CASE
            WHEN t.qty < 0 AND r.qty_berjalan != 0 THEN
                - (ABS(t.qty) * (r.total_aset_berjalan / NULLIF(r.qty_berjalan,0)))
            ELSE 0
        END,

        t.type,
        t.remarks,

        r.qty_berjalan_raw + t.qty,
        r.qty_berjalan + t.qty,

        -- PERHITUNGAN ASET (ANTI MINUS + HANDLE OPNAME)
    GREATEST(0,
        CASE

            -- 🔥 OPNAME (reset ke avg_price × qty baru)
            WHEN t.type = '~'
            THEN (r.qty_berjalan + t.qty) * t.harga_satuan

            -- 🔥 CROSSING MINUS KE >= 0
            WHEN t.qty > 0
                AND r.qty_berjalan_raw < 0
                AND (r.qty_berjalan_raw + t.qty) >= 0
            THEN (r.qty_berjalan_raw + t.qty) * t.harga_satuan

            -- PEMBELIAN NORMAL
            WHEN t.qty > 0
            THEN r.total_aset_berjalan + t.total_belanja

            -- TRANSAKSI MINUS
            WHEN r.qty_berjalan != 0
            THEN r.total_aset_berjalan
                - (ABS(t.qty) * (r.total_aset_berjalan / NULLIF(r.qty_berjalan,0)))

            ELSE r.total_aset_berjalan
        END
    )

    FROM running r
    JOIN ordered_trx t
      ON t.product_id = r.product_id
     AND t.rn = r.rn + 1
),

final AS (
    SELECT *,
        CASE
            WHEN type = '~' THEN harga_satuan

            -- recovery row (minus → >=0)
            WHEN qty_berjalan_raw >= 0
                 AND LAG(qty_berjalan_raw)
                     OVER (PARTITION BY product_id ORDER BY rn) < 0
            THEN harga_satuan

            WHEN qty_berjalan != 0
            THEN total_aset_berjalan / NULLIF(qty_berjalan,0)

            ELSE NULL
        END AS hpp_real
    FROM running
)

SELECT
    f.product_id,
    f.type,
    f.remarks,
    ABS(f.qty) AS qty,
    f.qty_berjalan_raw,
    f.qty_berjalan,
    f.harga_satuan,
    f.total_belanja,
    f.total_non_belanja,

    -- 🔥 HPP FINAL (MINUS FOLLOW RECOVERY)
    CASE
        WHEN f.type = '~'
        THEN f.harga_satuan

        WHEN f.qty_berjalan_raw < 0
        THEN (
            SELECT f2.hpp_real
            FROM final f2
            WHERE f2.product_id = f.product_id
              AND f2.rn > f.rn
              AND f2.qty_berjalan_raw >= 0
            ORDER BY f2.rn
            LIMIT 1
        )

        ELSE f.hpp_real
    END AS hpp_berjalan,

    f.total_aset_berjalan,
    f.created_at

FROM final f
ORDER BY f.product_id, f.rn;
