var successAlerts = document.getElementsByClassName('messages');
console.log(successAlerts[0])
for (var i = 0; i < successAlerts.length; i++) {
    let successAlert = successAlerts[i];
    setTimeout(function () {
        successAlert.style.display = 'none';
    }, 3000);
}

function closeAlert(button) {
    var alert = button.parentElement;
    alert.style.display = 'none';
}