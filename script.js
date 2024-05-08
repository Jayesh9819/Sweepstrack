// document.getElementById('chatButton').addEventListener('click', function() {
//     document.getElementById('userFormModal').style.display = 'block';
// });
document.getElementById('chatButton').addEventListener('click', function() {
    document.getElementById('userFormModal').style.display = 'flex'; // This should be 'flex', not 'block'
});


document.getElementsByClassName('close-button')[0].addEventListener('click', function() {
    document.getElementById('userFormModal').style.display = 'none';
});

document.getElementById('userInfoForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const name = document.getElementById('name').value;
    const referCode = document.getElementById('refercode').value;
    // Implement AJAX to send data to server
    fetch('../App/helper/saveUserData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `name=${encodeURIComponent(name)}&referCode=${encodeURIComponent(referCode)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '../../index.php/unkno'; // Redirect
            document.getElementById('userFormModal').style.display = 'none';
        } else {
            alert('Error saving data.');
        }
    });
});
