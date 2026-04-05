<?php

class Kesimpulan_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getKesimpulanData($bulan, $tahun)
    {
        // Define groupings (could be made dynamic but this matches the current Excel context)
        $groups = [
            [
                'label' => 'Pertamax',
                'stock_product_id' => 1,
                'nozzle_product_ids' => [1, 2]
            ],
            [
                'label' => 'Dex',
                'stock_product_id' => 3,
                'nozzle_product_ids' => [3, 4]
            ]
        ];

        $result = [];

        foreach ($groups as $group) {
            $groupData = [
                'produk' => $group['label'],
                'stok_awal' => 0,
                'penerimaan_bbm' => 0,
                'stok_akhir' => 0,
                'nozzles' => []
            ];

            // 1. Get Monthly Stock Summary
            $this->db->query("SELECT 
                                (SELECT stok_awal FROM stok WHERE produk_id = :prod_stock AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun ORDER BY tanggal ASC LIMIT 1) as awal,
                                SUM(kiriman_masuk) as total_kiriman,
                                (SELECT stok_akhir_fisik FROM stok WHERE produk_id = :prod_stock AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun ORDER BY tanggal DESC LIMIT 1) as akhir
                              FROM stok 
                              WHERE produk_id = :prod_stock AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun");
            
            $this->db->bind('prod_stock', $group['stock_product_id']);
            $this->db->bind('bulan', $bulan);
            $this->db->bind('tahun', $tahun);
            $stockSum = $this->db->single();

            if ($stockSum) {
                $groupData['stok_awal'] = $stockSum['awal'] ?? 0;
                $groupData['penerimaan_bbm'] = $stockSum['total_kiriman'] ?? 0;
                $groupData['stok_akhir'] = $stockSum['akhir'] ?? 0;
            }

            // 2. Get Nozzle Readings
            foreach ($group['nozzle_product_ids'] as $idx => $prod_id) {
                $nozzleLabel = ($idx + 1); // 1, 2...
                
                $this->db->query("SELECT 
                                    (SELECT totalisator_awal FROM penjualan_harian ph JOIN harian h ON ph.harian_id = h.id WHERE ph.produk_id = :prod_id AND MONTH(h.tanggal) = :bulan AND YEAR(h.tanggal) = :tahun ORDER BY h.tanggal ASC LIMIT 1) as tot_awal,
                                    (SELECT totalisator_akhir FROM penjualan_harian ph JOIN harian h ON ph.harian_id = h.id WHERE ph.produk_id = :prod_id AND MONTH(h.tanggal) = :bulan AND YEAR(h.tanggal) = :tahun ORDER BY h.tanggal DESC LIMIT 1) as tot_akhir
                                  FROM penjualan_harian ph
                                  JOIN harian h ON ph.harian_id = h.id
                                  WHERE ph.produk_id = :prod_id AND MONTH(h.tanggal) = :bulan AND YEAR(h.tanggal) = :tahun
                                  LIMIT 1");
                
                $this->db->bind('prod_id', $prod_id);
                $this->db->bind('bulan', $bulan);
                $this->db->bind('tahun', $tahun);
                $nozzleReading = $this->db->single();

                $totAwal = $nozzleReading['tot_awal'] ?? 0;
                $totAkhir = $nozzleReading['tot_akhir'] ?? 0;

                $groupData['nozzles'][] = [
                    'label' => 'Nozzel ' . $nozzleLabel,
                    'totalisator_awal' => $totAwal,
                    'totalisator_akhir' => $totAkhir,
                    'total_penjualan' => $totAkhir - $totAwal
                ];
            }

            $result[] = $groupData;
        }

        return $result;
    }
}
