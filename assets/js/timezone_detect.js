// JavaScript to fetch timezone and send it back to the server via AJAX
document.addEventListener('DOMContentLoaded', function() {
    var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    console.log(timezone);
    console.log("hiii");
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../Public/Pages/Chat/app/ajax/set_timezone.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Timezone set to:', this.responseText);
        }
    };
    xhr.send('timezone=' + encodeURIComponent(timezone));
});
