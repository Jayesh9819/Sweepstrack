<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Notifications</title>
    <style>
        .popup {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 300px;
            margin-top: 10px;
            z-index: 10000;
        }

        .popup-buttons {
            text-align: right;
            margin-top: 10px;
        }

        button {
            padding: 5px 10px;
            margin-left: 5px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        #closeAll {
            display: none;
            /* Initially hide the close all button */
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 10001;
        }
    </style>
</head>

<body>
    <button id="closeAll">Close All</button>
    <div id="popupContainer"></div>

    <script>
        function playNotificationSound() {
            let audio = new Audio('noti.wav');
            audio.play();
        }

        function createPopup(data) {
            let notification = data.message.trim();
            if (notification.toLowerCase() !== "no new transactions") {
                let popup = document.createElement('div');
                popup.classList.add('popup');
                popup.textContent = notification;
                popup.style.backgroundColor = {
                    high: 'red',
                    medium: 'yellow',
                    low: 'green'
                } [data.color] || 'grey'; // Default color if priority is undefined

                let closeButton = document.createElement('button');
                closeButton.textContent = 'Close';
                closeButton.onclick = function() {
                    popupContainer.removeChild(popup);
                    notificationCount--;
                    updateCloseAllVisibility();
                };

                let viewButton = document.createElement('button');
                viewButton.textContent = 'View';
                viewButton.onclick = function() {
                    window.location.href = data.url;
                };

                let buttonContainer = document.createElement('div');
                buttonContainer.classList.add('popup-buttons');
                buttonContainer.appendChild(viewButton);
                buttonContainer.appendChild(closeButton);

                popup.appendChild(buttonContainer);
                popupContainer.appendChild(popup);
                notificationCount++;
                updateCloseAllVisibility();
                playNotificationSound();
            }
        }

        let eventSource = new EventSource('../Public/Popup/bpop.php');
        let popupContainer = document.getElementById('popupContainer');
        let notificationCount = 0; // Initialize notification count

        eventSource.onmessage = function(event) {
            let data = JSON.parse(event.data);
            createPopup(data);
        };

        eventSource.onerror = function(event) {
            console.error("EventSource encountered an error: ", event);
        };

        document.getElementById('closeAll').addEventListener('click', function() {
            while (popupContainer.firstChild) {
                popupContainer.removeChild(popupContainer.firstChild);
            }
            notificationCount = 0;
            updateCloseAllVisibility();
        });

        function updateCloseAllVisibility() {
            const closeAllButton = document.getElementById('closeAll');
            closeAllButton.style.display = notificationCount > 2 ? 'block' : 'none';
        }
        setInterval(() => {
            fetch('../Public/Popup/delay.php') // Adjust path as needed
                .then(response => response.json())
                .then(data => {
                    if (data.message) { 
                        createPopup({
                            message: data.message,
                            color: data.color,
                            url:data.url
                        });
                    }
                })
                .catch(error => console.error('Failed to fetch periodic data:', error));
        }, 600000); // 600,000 ms is 10 minutes
    </script>

</body>

</html>