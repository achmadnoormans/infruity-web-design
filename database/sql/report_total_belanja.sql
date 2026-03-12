CREATE OR REPLACE VIEW report_total_belanja AS
WITH last_row AS (
    SELECT *
    FROM product_hpp p1
    WHERE created_at = (
        SELECT MAX(created_at)
        FROM product_hpp p2
        WHERE p2.product_id = p1.product_id
    )
)
SELECT
    p.product_id,

    SUM(CASE WHEN p.type='+' THEN p.total_belanja ELSE 0 END) AS total_belanja,

    SUM(CASE WHEN p.type IN ('-','~') THEN p.cogs ELSE 0 END) AS total_cogs,

    SUM(p.recovered_cogs) AS total_recovered_cogs,

    SUM(CASE WHEN p.type='~' THEN p.opname ELSE 0 END) AS total_opname,

    lr.total_aset_berjalan AS last_asset,

    (
        SUM(CASE WHEN p.type='+' THEN p.total_belanja ELSE 0 END)
        -
        SUM(CASE WHEN p.type='-' THEN p.cogs ELSE 0 END)
        -
        lr.total_aset_berjalan
    ) AS selisih_control

FROM product_hpp p
JOIN last_row lr ON lr.product_id = p.product_id
GROUP BY p.product_id, lr.total_aset_berjalan;