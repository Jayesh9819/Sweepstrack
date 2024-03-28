<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>CustCount - At your Service</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Weblux Digi">
<meta name="DC.title" content="CustCount">

<!-- Favicon -->
<link rel="shortcut icon" href="../assets/images/CustCountLogo.png">

<!-- Library / Plugin Css Build -->
<link rel="stylesheet" href="../assets/css/core/libs.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


<!-- qompac-ui Design System Css -->
<link rel="stylesheet" href="../assets/css/qompac-ui.minf700.css?v=1.0.1">

<!-- Custom Css -->
<link rel="stylesheet" href="../assets/css/custom.minf700.css?v=1.0.1">
<!-- Dark Css -->
<link rel="stylesheet" href="../assets/css/dark.minf700.css?v=1.0.1">

<!-- Customizer Css -->
<link rel="stylesheet" href="../assets/css/customizer.minf700.css?v=1.0.1">

<!-- RTL Css -->
<link rel="stylesheet" href="../assets/css/rtl.minf700.css?v=1.0.1">


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">


<!-- Google Font -->
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Then Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<!-- Finally Toastr JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- <script src=". vanillaEmojiPicker.js"></script> -->


<!-- Include Toastr CSS -->
<script>
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'throw'; // or 'none'

        // Destroy DataTable if it already exists
        if ($.fn.DataTable.isDataTable('#table_id')) {
            $('#table_id').DataTable().destroy();
        }

        // Initialize DataTable
        $('#table_id').DataTable({
            searching: true,
            paging: true,
            ordering: true
        });
        new $.fn.dataTable.Buttons(table, {
            buttons: [{
                extend: 'colvis',
                postfixButtons: ['colvisRestore'],
                collectionLayout: 'fixed two-column'
            }]
        });

        // Add the controls to the desired location
        $('#columnToggleDropdown').on('click', function() {
            table.buttons().container().appendTo($('#columnControls'));
        });

    });
</script>