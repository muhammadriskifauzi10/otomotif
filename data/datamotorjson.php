<?php
include 'servicedata.php';

class Datamotorjson extends Servicedata
{
    public function __construct()
    {
        parent::__construct();
        $nomor = 1;
        $data = [];
        $sql = "SELECT * FROM tb_barang";
        $this->stmt = $this->mysqli->prepare($sql);
        if ($this->stmt->execute()) {
            $result = $this->stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $harga = '<strong class="text-success">Rp. ' . number_format($row['harga'], 0, '.', '.') . '</strong>';
                    $edit = '<button type="button" class="btn btn-warning text-light" style="min-width: 75px;">Edit</button>';
                    $hapus = '<button type="button" class="btn btn-danger" style="min-width: 75px;">Hapus</button>';

                    $data[] = [
                        'nomor' => $nomor++,
                        'motor' => $row['motor'],
                        'harga' => $harga,
                        'edit' => $edit,
                        'hapus' => $hapus,
                    ];
                }
            }
        }

        echo json_encode($data);
    }
}

$datamotorjson = new Datamotorjson();
