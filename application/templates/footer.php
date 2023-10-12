<script src="public/js/bootstrap.bundle.min.js"></script>
<script src="public/js/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="public/js/universal.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#datatableKategori').DataTable({
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
                {
                    "data": 'edit'
                },
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
    });

    $(document).ready(function() {
        var table = $('#datatableMotor').DataTable({
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
                {
                    "data": 'edit'
                },
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
                                        ` + responseParse['data'].join(" ").trim() +` 
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