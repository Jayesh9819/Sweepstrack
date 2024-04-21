<!-- Include Bootstrap and other CSS libraries -->
<?php
if (isset($_SESSION['role'])) {


    $role = $_SESSION['role'];
    if ($role != 'User') {
        echo '<script src="../Public/Chats/globalNotifications.js" > </script>';
    } else {
        echo '<script src="../Public/Chats/usernot.js" > </script>';
    }
}
?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {


        let lastSeenUpdate = function() {
            $.get('../Public/Pages/Chat/app/ajax/update_last_seen.php')
                .done(function(data) {
                    console.log('Success:', data); // Successful response handling
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus); // Error handling
                });
        };

        lastSeenUpdate(); // Initial call
        setInterval(lastSeenUpdate, 10000); // Set to run every 10 seconds
    });
</script>
<!-- jQuery Library - Load this first to ensure it's available for all jQuery-dependent scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- Toastr Notification Library - Depends on jQuery, so it comes after jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Bootstrap Bundle JS - Includes Popper; necessary for Bootstrap components; comes after jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Core Library and Plugin Scripts -->
<script src="../assets/js/core/libs.min.js"></script>
<script src="../assets/js/core/external.min.js"></script>
<script src="../assets/js/vendors.min.js"></script>
<script src="../assets/js/plugins/select2.js" defer></script>
<script src="../assets/js/plugins/slider-tabs.js" defer></script>
<script src="../assets/vendor/lodash/lodash.min.js"></script>
<script src="../assets/js/iqonic-script/utility.min.js"></script>
<script src="../assets/js/iqonic-script/setting.min.js"></script>
<script src="../assets/js/setting-init.js"></script>
<script src="../assets/js/charts/widgetchartsf700.js?v=1.0.1" defer></script>
<script src="../assets/js/charts/dashboardf700.js?v=1.0.1" defer></script>
<script src="../assets/js/qompac-uif700.js?v=1.0.1" defer></script>
<script src="../assets/js/sidebarf700.js?v=1.0.1" defer></script>
<script src="../assets/js/pages/data-table.js"></script>
<script src="../assets/js/pages/chat-popup.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/vendor_components/datatable/datatables.min.js"></script>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('../Public/Pages/Common/service-worker.js')
            .then((registration) => {
                console.log('Service Worker registered with scope:', registration.scope);
            })
            .catch((error) => {
                console.error('Service Worker registration failed:', error);
            });
    }
</script>