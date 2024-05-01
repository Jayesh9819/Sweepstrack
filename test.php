<?php 
include './App/db/db_connect.php';
$resultPage = $conn->query("SELECT branch.name AS bname, page.name FROM branch JOIN page ON page.bid = branch.bid WHERE page.status = 1");
$resultBranch = $conn->query("SELECT name FROM branch ");
$branches = []; // Array to hold all rows from $resultBranch

while ($row = $resultBranch->fetch_assoc()) {
    $branches[] = $row; // Add each row to the $branches array
}

$pages=[];
while ($row = $resultPage->fetch_assoc()) {
    $pages[] = $row; // Add each row to the $branches array
}


print_r($branches);
echo '<br>';
print_r($pages)


?>
<?php
function generateDynamicCheckboxScript($branchDropdownId, $checkboxContainerId, $pagesData) {
    $script = "<script>
        const branchSelect = document.getElementById('$branchDropdownId');
        const checkboxContainer = document.getElementById('$checkboxContainerId');
        const pagesData = " . json_encode($pagesData) . ";

        // Function to update checkbox options based on selected branch
        function updateCheckboxOptions() {
            const selectedBranch = branchSelect.value;
            // Clear previous checkboxes
            checkboxContainer.innerHTML = '';
            // Populate checkboxes based on selected branch
            pagesData.forEach(page => {
                if (page.bname === selectedBranch) {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'selectedPages[]';
                    checkbox.value = page.name;
                    checkbox.id = page.name; // Optional: Assigning an ID for labels to work
                    const label = document.createElement('label');
                    label.textContent = page.name;
                    label.setAttribute('for', page.name); // Associating label with checkbox
                    checkboxContainer.appendChild(checkbox);
                    checkboxContainer.appendChild(label);
                    checkboxContainer.appendChild(document.createElement('br'));
                }
            });
        }

        // Event listener for branch selection change
        branchSelect.addEventListener('change', updateCheckboxOptions);

        // Initial call to populate checkboxes based on default selected branch
        updateCheckboxOptions();
    </script>";

    return $script;
}

// Your PHP code to retrieve branches and pages arrays
$branches = [
    ['name' => 'bhayandar'],
    ['name' => 'Mumbai']
];

$pages = [
    ['bname' => 'bhayandar', 'name' => 'work'],
    ['bname' => 'bhayandar', 'name' => 'Zack Sweepstakes'],
    ['bname' => 'bhayandar', 'name' => 'Regina Dutch'],
    ['bname' => 'bhayandar', 'name' => 'Clay Johnson'],
    ['bname' => 'bhayandar', 'name' => 'Sally Hamston'],
    ['bname' => 'Mumbai', 'name' => 'Jayesh'],
    ['bname' => 'Mumbai', 'name' => 'CDD'],
    ['bname' => 'Mumbai', 'name' => 'JDD']
];

// Generate JavaScript code for dynamic checkboxes
$dynamicCheckboxScript = generateDynamicCheckboxScript('branch', 'checkboxContainer', $pages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Checkboxes</title>
</head>
<body>
    <form>
        <label for="branch">Select Branch:</label>
        <select id="branch" name="branch">
            <option value="">Select Branch</option>
            <?php foreach ($branches as $branch): ?>
                <option value="<?php echo $branch['name']; ?>"><?php echo $branch['name']; ?></option>
            <?php endforeach; ?>
        </select>

        <div id="checkboxContainer"></div>
    </form>

    <?php echo $dynamicCheckboxScript; ?>
</body>
</html>
