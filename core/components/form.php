<?php

function input($type, $name, $label, $value = '', $readonly = '', $disabled = '')
{
    echo "<div class='w-1/2 p-2'>";
    echo "<label for='$name' class='block text-gray-700 font-bold mb-2'>" . ucfirst($label) . ":</label>";
    echo "<input type='$type' id='$name' name='$name' value='" . htmlspecialchars($value) . "' $readonly $disabled class='w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300' />";
    echo "</div>";
}

function select($name, $options = [], $label = '', $selected = '', $readonly = '', $disabled = '')
{
    echo "<div class='w-1/2 p-2'>";
    echo "<label for='$name' class='block text-gray-700 font-bold mb-2'>" . ucfirst($label) . ":</label>";
    echo "<select id='$name' name='$name' $readonly $disabled class='w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300'>";
    foreach ($options as $value => $label) {
        $isSelected = $value == $selected ? 'selected' : '';
        echo "<option value='" . htmlspecialchars($value) . "' $isSelected>" . htmlspecialchars($label) . "</option>";
    }
    echo "</select>";
    echo "</div>";
}

function textarea($name, $value = '', $label = '', $readonly = '', $disabled = '')
{
    echo "<div class='w-full p-2'>";
    echo "<label for='$name' class='block text-gray-700 font-bold mb-2'>" . ucfirst($label) . ":</label>";
    echo "<textarea id='$name' name='$name' $readonly $disabled class='w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300'>" . htmlspecialchars($value) . "</textarea>";
    echo "</div>";
}

function button($type = 'submit', $label = 'Submit', $onclick = '')
{
    echo "<div class='w-full p-2'>";
    echo "<button type='$type' onclick='$onclick'>$label</button>";
    echo "</div>";
}


function form(
    $data,
    $model,
    $type = 'create'
) {
    $label = $model->label;
    $form = $model->columns();

    foreach ($form as $key => $column) {
        if (isset($column['show_in_form']) && $column['show_in_form'] === false) {
            unset($form[$key]);
        }
    }
?>

    <form method="POST" action="/<?php echo $model->table; ?>/<?php echo $type === 'edit' ? 'update' : 'create'; ?>" type="<?php echo $type; ?>" class="w-full bg-white p-6 rounded shadow flex flex-row flex-wrap">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data[$model->primaryKey] ?? ''); ?>">
        <?php foreach ($form as $field => $props) {
            switch ($props['type_input']) {
                case 'text':
                case 'email':
                case 'password':
                case 'number':
                    if (isset($props['auto_increment']) && $props['auto_increment'] === true) {
                        break;
                    }
                    if ($type == 'view' && !$props['show_in_list']) {
                        break;
                    }
                    input($props['type_input'], $field, $props['label'] ?? '', $data[$field] ?? '', '', '',);
                    break;
                case 'select':
                    $options = [];
                    if (isset($props['foreign_key'])) {
                        // fetch options from foreign key table
                        $fkTable = $props['foreign_key']['table'];
                        $fkColumn = $props['foreign_key']['column'];
                        $fkDisplayColumn = $props['foreign_key']['display_column'];
                        $fkModelClass = "\\Models\\" . ucfirst(rtrim($fkTable, 's'));
                        if (class_exists($fkModelClass)) {
                            $fkModel = new $fkModelClass();
                            $fkData = $fkModel->all();
                            foreach ($fkData as $row) {
                                $options[$row[$fkColumn]] = $row[$fkDisplayColumn];
                            }
                        }
                    } elseif (isset($props['select_options'])) {
                        $options = $props['select_options'];
                    }
                    select($field, $options, $props['label'] ?? '', $data[$field] ?? '', '', '');
                    break;
                case 'text_area':
                    textarea($field, $data[$field] ?? '', $props['label'] ?? '', '', '');
                    break;
            }
        }

        if ($type !== 'view') {
            echo "
        <div class='w-full p-2'><button type='submit' class='bg-tomato text-white text-[16px] px-4 py-2 rounded hover:bg-red-600'>" . (isset($type) && $type === 'edit' ? 'Guardar ' . $label : 'Crear ' . $label) . "</button></div>";
        }
        ?>
    </form>
<?php
}

// Mapea tipos de datos a tipos de input HTML
function type_data($type)
{
    switch ($type) {
        case 'int':
        case 'bigint':
        case 'smallint':
            return 'number';
        case 'varchar':
        case 'text':
        case 'email':
        case 'password':
            return 'text';
        case 'date':
            return 'date';
        case 'datetime':
            return 'datetime-local';
        case 'boolean':
            return 'checkbox';
        default:
            return 'text';
    }
}
