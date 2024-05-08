<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="manifest.json">
    <title>QuickChat</title>
</head>
<body>
    <!-- Your PHP content here -->

    <button id="addToHomeScreenButton" style="display: none;">Add to Home Screen</button>
<h1>Helloo Successsssssss<h1>
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('service-worker.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }



    let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent the default prompt from showing
  e.preventDefault();
  // Store the event for later use
  deferredPrompt = e;
  // Optionally show your custom "Add to Home Screen" button or link
  showAddToHomeScreenButton();
});

function showAddToHomeScreenButton() {
  // Display your custom button or link and attach an event listener to trigger the prompt
  const addToHomeScreenButton = document.getElementById('addToHomeScreenButton');
  addToHomeScreenButton.style.display = 'block';
  addToHomeScreenButton.addEventListener('click', () => {
    // Show the prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('User accepted the A2HS prompt');
      } else {
        console.log('User dismissed the A2HS prompt');
      }
      // Clear the deferredPrompt variable
      deferredPrompt = null;
    });
  });
}

    </script>
    
</body>
</html>
