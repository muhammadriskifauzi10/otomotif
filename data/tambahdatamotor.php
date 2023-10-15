<?php
include 'servicedata.php';

class Tambahdatamotor extends Servicedata
{
    public function __construct()
    {
        parent::__construct();
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
}

$tambahdatamotor = new Tambahdatamotor();

?>

<form class="modal-content" id="adddatamotor" onsubmit="addDataMotor(event)" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalLabel">Tambah Data Motor</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-2">
            <label for="motor" class="form-label">Motor</label>
            <input type="text" name="motor" class="form-control" id="motor">
        </div>
        <div class="mb-2">
            <label for="kategori_id" class="form-label">kategori</label>
            <select name="kategori_id" class="form-select" id="kategori_id">
                <?php if (!empty($tambahdatamotor->getDataKategori())) : ?>
                    <?php foreach ($tambahdatamotor->getDataKategori() as $row) : ?>
                        <option value="<?= $row['id']; ?>"><?= $row['kategori']; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-2">
            <label for="harga" class="form-label">Harga</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" name="harga" class="form-control formatrupiah" id="harga">
            </div>
        </div>
        <div class="mb-2">
            <label for="gambar" class="form-label">Gambar</label>
            <input type="file" name="gambar" class="form-control" id="gambar">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-danger" style="width: 90px;">Simpan</button>
    </div>
</form>

<script>
    $('.formatrupiah').maskMoney({
        allowNegative: false,
        precision: 0,
        thousands: '.'
    });
</script>