<?php
include 'application/Services.php';

class Index extends Services
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['id'])) {
            echo json_encode([
                'status' => 200,
                'message' => true,
                'data' => 'oke',
            ]);
            exit;
        }
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
            <?php if($auth->authUserRoleID == 1): ?>
            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                        <a href="data.php" class="btn btn-primary">Kelola Data</a>
                    </div>
                </li>
            </ul>
            <?php endif; ?>
            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="all" id="all" checked disabled>
                        <label for="all" class="form-label user-select-none m-0">Semua</label>
                    </div>
                </li>
                <?php if (!empty($index->getDataKategori())) : ?>
                    <?php foreach ($index->getDataKategori() as $row) : ?>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="<?= strtolower($row['kategori']); ?>" id="<?= strtolower($row['kategori']); ?>">
                                <label for="<?= strtolower($row['kategori']); ?>" class="form-label user-select-none m-0">
                                    <?= $row['kategori']; ?>
                                </label>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                                        <button type="button" class="btn btn-primary" onclick="showModal(<?= $row['id']; ?>)">Pesan</button>
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