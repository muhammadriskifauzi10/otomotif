<?php
include 'servicedata.php';

class Datakategorijson extends Servicedata
{
    public function __construct()
    {
        parent::__construct();
        $nomor = 1;
        $data = [];
        $sql = "SELECT * FROM tb_kategori";
        $this->stmt = $this->mysqli->prepare($sql);
        if ($this->stmt->execute()) {
            $result = $this->stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $edit = '<button type="button" class="btn btn-warning text-light" style="min-width: 75px;">Edit</button>';
                    
                    $hapus = '<button type="button" class="btn btn-danger" style="min-width: 75px;">Hapus</button>';

                    $data[] = [
                        'nomor' => $nomor++,
                        'kategori' => $row['kategori'],
                        'edit' => $edit,
                        'hapus' => $hapus,
                    ];
                }
            }
        }

        echo json_encode($data);
    }
}

$datakategorijson = new Datakategorijson();
