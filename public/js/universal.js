$(document).ready(function() {
    if($("#all").is(":checked")) {
        onlyAllChecked()
    }

    var arrItems = []
    var listCategory = document.querySelectorAll("input[type='checkbox']")
    for(let i = 0; i < listCategory.length; i++) {
        listCategory[i].addEventListener("click", function() {
            if(this.getAttribute("name") === "all") {
                onlyAllChecked()
                $("#all").attr("disabled", true)
                $("#items-matic").show()
                $("#items-sport").show()
                $("#items-cub").show()
                arrItems = []
            }
            else {
                openAllChecked()
                $("#items-matic").hide()
                $("#items-sport").hide()
                $("#items-cub").hide()
                var item = "items-" + $(this).attr("name")
                if($(this).is(":checked")) {
                    arrItems.push(item)
                }
                else {
                    var removeItems = arrItems.filter(value => value != item)
                    arrItems = []
                    removeItems.map(value => arrItems.push(value))
                }
                arrItems.map(value => $("#" + value).show())
            }
        })
    }
})

function openAllChecked() {
    $("#all").prop("checked", false)
    $("#all").removeAttr("disabled")
}

function onlyAllChecked() {
    $("input[name='matic']").prop("checked", false)
    $("input[name='sport']").prop("checked", false)
    $("input[name='cub']").prop("checked", false)
}
