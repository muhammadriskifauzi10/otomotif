<?php
include 'application/Services.php';

class Data extends Services
{
    public function getDataKategori()
    {
        $data = [];
        $sql = "SELECT * FROM tb_kategori";
        $this->stmt = $this->mysqli->prepare($sql);
        if ($this->stmt->execute()) {
            $result = $this->stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }
}
$data = new Data();

include 'application/templates/head.php';
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-lg-2">
            <ul class="list-group mb-3">
                <li class="list-group-item choice-edit-data active" style="cursor: pointer;" onclick="listEditData(this, 'kategori')">
                    Data Kategori
                </li>
                <li class="list-group-item choice-edit-data" style="cursor: pointer;" onclick="listEditData(this, 'motor')">
                    Data Motor
                </li>
            </ul>
        </div>
        <div class="col-lg-10">
            <h3>Data</h3>
            <hr />
            <!-- Kategori -->
            <div class="mt-3" id="data-kategori">
                <button type="button" class="btn btn-primary">Tambah Data</button>
                <div class="row mt-3">
                    <div class="col-lg-4 w-100">
                        <table class="table w-100" id="datatableKategori">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Hapus</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Motor -->
            <div class="mt-3" id="data-motor" style="display: none;">
                <button type="button" class="btn btn-primary">Tambah Data</button>
                <div class="row mt-3">
                    <div class="col-lg-4 w-100">
                        <table class="table w-100" id="datatableMotor">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Motor</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Hapus</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'application/templates/footer.php'; ?>