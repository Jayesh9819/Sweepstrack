<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
     <!-- Include your JavaScript code -->
</head>
<body>
    <!-- Your main content goes here -->
    <div id="content"></div>

    <script>
        function fetchAndUpdatePage() {
    $.ajax({
        url: '../Public/Pages/Portal/portal_exchat.php',
        type: 'GET',
        success: function(data) {
            $('#content').html(data); // Update the content with fetched data
        },
        error: function(xhr, status, error) {
            console.log('Error fetching page content');
        }
    });
}

fetchAndUpdatePage();

    </script>
</body>
</html>
