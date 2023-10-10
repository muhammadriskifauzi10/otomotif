<?php
include 'application/Services.php';

class Index extends Services
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getData($id = null)
    {
        $data = [];
        if ($id) {
            $sql = "SELECT * FROM tb_barang WHERE kategori_id=$id";
            $this->stmt = $this->mysqli->prepare($sql);

            if ($this->stmt->execute()) {
                $result = $this->stmt->get_result();
            }
        } else {
            $sql = "SELECT * FROM tb_barang";
            $this->stmt = $this->mysqli->prepare($sql);

            if ($this->stmt->execute()) {
                $result = $this->stmt->get_result();
            }
        }

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
    public function __destruct()
    {
        $this->mysqli->close();
        $this->stmt->close();
    }
}
$index = new Index();

include 'application/templates/head.php';
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-lg-2">
            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="all" id="all" checked disabled>
                        <label for="all" class="form-label user-select-none m-0">Semua</label>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="matic" id="matic">
                        <label for="matic" class="form-label user-select-none m-0">Matic</label>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="sport" id="sport">
                        <label for="sport" class="form-label user-select-none m-0">Sport</label>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="cub" id="cub">
                        <label for="cub" class="form-label user-select-none m-0">Cub</label>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-lg-10">
            <h3>Pilih Motor Favorit Anda</h3>
            <hr />
            <!-- Matic -->
            <div class="mt-3" id="items-matic">
                <h5 class="my-3">Matic</h5>
                <div class="row">
                    <?php foreach ($index->getData(1) as $row) : ?>
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <img src="public/img/matic/<?= $row['gambar']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title"><?= htmlspecialchars($row['barang'], true); ?></h4>
                                    <h5>Rp. <?= htmlspecialchars(number_format($row['harga'], 0, '.', '.'), true); ?></h5>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">Pesan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Sport -->
            <div class="mt-3" id="items-sport">
                <h5 class="my-3">Sport</h5>
                <div class="row">
                    <?php foreach ($index->getData(2) as $row) : ?>
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <img src="public/img/sport/<?= $row['gambar']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title"><?= htmlspecialchars($row['barang'], true); ?></h4>
                                    <h5>Rp. <?= htmlspecialchars(number_format($row['harga'], 0, '.', '.'), true); ?></h5>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">Pesan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Cub -->
            <div class="mt-3" id="items-cub">
                <h5 class="my-3">Cub</h5>
                <div class="row">
                    <?php foreach ($index->getData(3) as $row) : ?>
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <img src="public/img/cub/<?= $row['gambar']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title"><?= htmlspecialchars($row['barang'], true); ?></h4>
                                    <h5>Rp. <?= htmlspecialchars(number_format($row['harga'], 0, '.', '.'), true); ?></h5>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">Pesan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'application/templates/footer.php'; ?>