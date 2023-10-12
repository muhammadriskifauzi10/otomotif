<script src="public/js/bootstrap.bundle.min.js"></script>
<script src="public/js/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="public/js/universal.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            "ajax": {
                url: "data/datakategorijson.php",
                dataSrc: ""
            },
            "columns": [
                {
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

    function showModal(id) {
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                id: id
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
                    $("#universalModal").append(`
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    `)
                    $("#modal").modal("show")
                }
            }
        })

    }

    function listEditData(value) {
        if (value === "kategori") {
            console.log("oke");
        } else if (value === "motor") {
            console.log("sip")
        }
    }
</script>
</body>

</html>