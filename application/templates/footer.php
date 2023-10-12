<script src="public/js/bootstrap.bundle.min.js"></script>
<script src="public/js/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="public/js/sweetalert2.min.js"></script>
<script src="public/js/universal.js"></script>

<script>
    $(document).ready(function() {
        var tableKategori = $('#datatableKategori').DataTable({
            "ajax": {
                url: "data/datakategorijson.php",
                dataSrc: ""
            },
            "columns": [{
                    "data": 'nomor'
                },
                {
                    "data": 'kategori'
                },
                // {
                //     "data": 'edit'
                // },
                {
                    "data": 'hapus'
                },
            ]
            // "order": [
            //     [1, 'asc']
            // ],
            // scrollY: "700px",
            // scrollX: true,
            // scrollCollapse: true,
            // paging:         false,
            // fixedColumns: {
            //     left: 3,
            // }
        });

        var tableMotor = $('#datatableMotor').DataTable({
            "ajax": {
                url: "data/datamotorjson.php",
                dataSrc: ""
            },
            "columns": [{
                    "data": 'nomor'
                },
                {
                    "data": 'motor'
                },
                {
                    "data": 'harga'
                },
                // {
                //     "data": 'edit'
                // },
                {
                    "data": 'hapus'
                },
            ],
            // "order": [
            //     [1, 'asc']
            // ],
            // paging:         false,
            // fixedColumns: {
            //     left: 3,
            // }
        });
    });

    // Show form add data
    function addData(value) {
        if (value == "kategori") {
            $("#universalModal").empty()
            $("#universalModal").addClass("modal-dialog-centered")
            $("#universalModal").append(`
                <form class="modal-content" id="adddatakategory" onsubmit="addDataKategory(event)" autocomplete="off">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabel">Tambah Data Kategori</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="kategpri" class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="width: 90px;">Tutup</button>
                        <button type="submit" name="postkategori" class="btn btn-primary" style="width: 90px;">Simpan</button>
                    </div>
                </form>
                `)
        }
        if (value === "motor") {

        }
        $("#modal").modal("show")
    }
    // Add data kategory
    function addDataKategory(e) {
        e.preventDefault()
        let formData = $("#adddatakategory").serialize()

        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                let responseParse = JSON.parse(response)
                if (responseParse.message === true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Berhasil Ditambahkan!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $("#datatableKategori").DataTable().ajax.reload()
                    $("#modal").modal("hide")
                    $("#universalModal").empty()
                }
            }
        })
    }
    // Show form remove data kategory
    function removeDataKetegory(id) {
        $("#universalModal").empty()
        $("#universalModal").addClass("modal-dialog-centered")
        $("#universalModal").append(`
            <form class="modal-content" id="goremovedatakategory" onsubmit="goremoveDataKategory(event)" autocomplete="off">
                <input type="hidden" name="removedatakategoriperid" value="` + id + `">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Hapus Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin hapus data?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="width: 90px;">Tutup</button>
                    <button type="submit" name="postkategori" class="btn btn-primary" style="width: 90px;">Ya!</button>
                </div>
            </form>
            `)
        $("#modal").modal("show")
    }
    // Remove data kategory
    function goremoveDataKategory(e) {
        e.preventDefault()
        let formData = $("#goremovedatakategory").serialize()
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                let responseParse = JSON.parse(response)
                if (responseParse.message === true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Berhasil Dihapus!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $("#datatableKategori").DataTable().ajax.reload()
                    $("#modal").modal("hide")
                    $("#universalModal").empty()
                }
            }
        })
    }

    function showModal(id) {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                idDataBarang: id
            },
            beforeSend: function() {
                $("#parent-spinner-box").show()
            },
            success: function(response) {
                let responseParse = JSON.parse(response)
                if (responseParse.message === true) {
                    $("#parent-spinner-box").hide()
                    $("#universalModal").empty()
                    $("#universalModal").addClass("modal-dialog-centered")
                    $("#universalModal").addClass("modal-lg")
                    $("#universalModal").append(`
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalLabel">Detail Motor</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <img src="public/img/motor/beat.webp" class="card-img-top">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <h6>Spesifikasi</h6>
                                    <hr/>
                                    <ol type="1">
                                        ` + responseParse['data'].join(" ").trim() + ` 
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="width: 90px;">Tutup</button>
                            ` + responseParse['permission'] + `
                        </div>
                    </div>
                    `)
                    $("#modal").modal("show")
                }
            }
        })

    }

    function listEditData(e, value) {
        for (let i = 0; i < $(".choice-edit-data").length; i++) {
            $(".choice-edit-data")[i].classList.remove('active')
        }
        if (value === "kategori") {
            e.classList.add('active')
            $("#data-kategori").show()
            $("#data-motor").hide()
        } else if (value === "motor") {
            e.classList.add('active')
            $("#data-kategori").hide()
            $("#data-motor").show()
        }
    }
</script>
</body>

</html>