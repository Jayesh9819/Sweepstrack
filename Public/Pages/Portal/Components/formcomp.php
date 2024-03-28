<?php

$Submit = '<button type="submit" class="btn btn-primary">Submit</button>';
$Cancel = '<button type="reset" class="btn btn-danger">Reset</button>';

function fhead($title = "", $heading = "", $faction = "")
{
    $formstart = '<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">' . $title . '</h4>
                    <p class="text-muted mb-0">' . $heading . '</p>
                </div>
                <div class="card-body">
                    <form action=' . $faction . ' method="POST">';
    return $formstart;
}
function field($label, $type, $id, $placeholder, $value = "", $required = "required", $ftype = "")
{
    $html = '<div class="form-group">
                <label class="form-label" for="' . $id . '">' . $label . '</label>
                <input type="' . $type . '" name="' . $id . '" class="form-control" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $placeholder . '" ' . $required . ' ' . $ftype . '>
            </div>';

    return $html;
}


function select($label, $id, $name, $options, $selectedOption = null)
{
    $html = '<div class="form-group">
                <label class="form-label" for="' . $id . '">' . $label . '</label>
                <select class="form-select" id="' . $id . '" name="' . $name . '">';

    foreach ($options as $option) {
        $selected = ($option == $selectedOption) ? 'selected' : '';
        $html .= '<option ' . $selected . ' >' . $option . '</option>';
    }

    $html .= '</select>
            </div>';

    return $html;
}
function cfield($label, $id, $name, $value, $isChecked = false)
{
    $checkedAttribute = $isChecked ? 'checked' : '';

    $html = '<div class="form-group">';
    $html .= '<label for="' . $id . '">' . $label . '</label>';
    $html .= '<input type="checkbox" id="' . $id . '" name="' . $name . '" value="' . $value . '" ' . $checkedAttribute . '>';
    $html .= '</div>';

    return $html;
}
function sbox($label, $id, $isChecked = false)
{
    // Use the ternary operator to set the 'checked' attribute
    $checkedAttribute = $isChecked ? 'checked' : '';

    $html = '<div class="form-check form-switch">';
    $html .= '<input class="form-check-input" type="checkbox" name="' . $id . '" role="switch" id="' . $id . '" ' . $checkedAttribute . '>';
    $html .= '<label class="form-check-label" for="' . $id . '">' . $label . '</label>';
    $html .= '</div>';
    $html .= '<script>
        // Get the switch checkbox element
        var switchCheckbox = document.getElementById("' . $id . '");
        
        // Add an event listener to handle the click event
        switchCheckbox.addEventListener("click", function() {
            // Update the value based on the checkbox state
            var newValue = switchCheckbox.checked ? 1 : 0;
            
            // Log the new value (you can replace this with your desired logic)
            console.log(newValue);
        });
    </script>';

    return $html;
}

$formend = ' </form>

</div> <!-- end card-body -->
</div> <!-- end card-->
</div> <!-- end col -->
</div>';
?>
<script>
    var switchCheckbox = document.getElementById('flexSwitchCheckChecked');
    switchCheckbox.addEventListener('click', function() {
        switchCheckbox.checked = !switchCheckbox.checked;
        console.log('Switch checkbox value changed to:', switchCheckbox.checked ? 1 : 0);
    });
</script>