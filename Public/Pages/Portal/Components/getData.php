<br>
<br>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Deposit Data</h4>
                <p class="text-muted mb-0">
                    Add some info
                </p>
            </div>
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <?php
                        include '../../../App/db/db_connect.php';
                        $sql = "SELECT id, name, user_id, reflect_amount, bonus_amount, platform, password, money, by_username, by_role, added_time FROM deposits";

                        $result = $conn->query($sql);

                        // Check if there are results

                        if ($result->num_rows > 0) {
                            // Start table
                            // echo '<table border="1">';
                            echo '<tr><th>ID</th><th>Name</th><th>User ID</th><th>Reflect Amount</th><th>Bonus Amount</th><th>Platform</th><th>Password</th><th>Money</th><th>By Username</th><th>By Role</th><th>Added Time</th></tr>';

                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<thead><tr><tbody>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['user_id']}</td>
                                    <td>{$row['reflect_amount']}</td>
                                    <td>{$row['bonus_amount']}</td>
                                    <td>{$row['platform']}</td>
                                    <td>{$row['password']}</td> <!-- Consider if you really want to display passwords -->
                                    <td>{$row['money']}</td>
                                    <td>{$row['by_username']}</td>
                                    <td>{$row['by_role']}</td>
                                    <td>{$row['added_time']}</td>
                                  </tr></tbody>";
                            }

                            // End table
                            echo '</table>';
                        } else {
                            echo "0 results";
                        }

                        // Close connection
                        $conn->close();
                        ?>


            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div> <!-- end row-->