// function addEventToCheckboxes() {
// document.addEventListener("DOMContentLoaded", function () {
//     var checkboxes = document.querySelectorAll('input[type="checkbox"]');
//     var selectedFiles = [];

//     checkboxes.forEach(function (checkbox) {
//         checkbox.addEventListener('change', function () {
//             var fileId = checkbox.dataset.file_id;
//             if (checkbox.checked) {
//                 selectedFiles.push(fileId);
//             } else {
//                 var index = selectedFiles.indexOf(fileId);
//                 if (index !== -1) {
//                     selectedFiles.splice(index, 1);
//                 }
//             }
//             document.getElementById('selected_files').value = selectedFiles.join(',');
//         });
//     });
// });
// }

// addEventToCheckboxes();
function checkout(fileId) {
    // console.log(fileId);
    var csrf = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: "checkOutFile",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrf
        },
        data: {
            fileId: fileId,
        },
        success: function (result) {
            if (!result['status']) {
                alert(result['msg'])
                return;
            }
            $('#avilable_' + fileId).text("Yes");
            $('#download_' + fileId).text("-----");
            $('#upload_' + fileId).text("-----");
            $('#action_' + fileId).html('<input type="checkbox" data-file_id="' + fileId + '"name="file_input[]" value="' + fileId + '" > ');
            // addEventToCheckboxes();

            // $('#action_' + fileId).html('<a class="btn-danger checkout" onclick="checkout(' + fileId + ')" >Ceckout</a>');
            $.toast({
                heading: 'Success',
                text: result['msg'],
                showHideTransition: 'slide',
                bgColor: '#3c763d',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function uploadFile(e) {
    if (!confirm('Are you sure ?'))
        return;

    var csrf = $('meta[name="csrf-token"]').attr('content');
    var fileId = e.dataset.file_id;
    var file = e.files[0];
    var formData = new FormData();
    formData.append('file', file);
    formData.append('fileId', fileId);
    $.ajax({
        url: "updateFile",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrf
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (!result['status']) {
                alert(result['msg'])
                return;
            }
            e.style.display = 'none';
            $.toast({
                heading: 'Success',
                text: result['msg'],
                showHideTransition: 'slide',
                bgColor: '#3c763d',
                icon: 'success'
            })
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });

}