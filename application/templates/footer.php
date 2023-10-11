<script src="public/js/bootstrap.bundle.min.js"></script>
<script src="public/js/jquery.min.js"></script>
<script src="public/js/universal.js"></script>

<script>
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
</script>
</body>

</html>