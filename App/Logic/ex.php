<!-- //ajax exp -->
<?php
include './db_connect.php';

$response = array('success' => false, 'message' => '');

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Perform a query to delete the product from the cart
    $delete_sql = "DELETE FROM cart WHERE product_id = $product_id";

    if ($conn->query($delete_sql) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Item removed from the cart successfully!";
    } else {
        $response['message'] = "Error deleting the item from the cart: " . $conn->error;
    }
} else {
    $response['message'] = "Invalid product ID.in it";
}

$conn->close();

// Send the response back to the JavaScript with the correct content type
header('Content-Type: application/json');
echo json_encode($response);

?>


<!-- Ajax call in the from=ntend -->

<a href="javascript:void(0);" class="delete-button" onclick="deleteCartItem('<?php echo $product['id']; ?>')">
    <i class="fa-solid fa-xmark"></i>
</a>


<script>
    function deleteCartItem(product_id) {
        if (confirm("Are you sure you want to remove this item from the cart?")) {
            // Create an XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Specify the request method and URL (using POST for data modification)
            xhr.open("POST", "../App/Logic/order.php?action=deletecart", true);

            // Set up the content type for sending data
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the product_id and action as POST data
            xhr.send("product_id=" + product_id);

            // Handle the response
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Parse the response
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Item removed from the cart successfully!");
                            location.reload();

                            // Update the cart display or perform other actions
                        } else {
                            alert("Error removing item from the cart mm: " + response.message);
                        }
                    } else {
                        // Handle other HTTP status codes (e.g., 400, 404, 500)
                        alert("Error: " + xhr.statusText);
                    }
                }
            };
        }
    }
</script>