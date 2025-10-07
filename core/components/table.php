<?php

function table(
    $head,
    $rows,
    $current_page = 1,
    $pages = 1,
    $limit = 10,
    $actions = [],
    $primary_key = 'id'
) {
    global $model;
?>


    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <?php
                        foreach ($head as $column) {
                            echo "<th class='px-6 py-3 text-left text-[14px] tracking-wider'>" . htmlspecialchars($column) . "</th>";
                        }
                        if (!empty($actions)) {
                            echo "<th class='px-6 py-3 text-left text-[14px] tracking-wider'>Acciones</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    foreach ($rows as $row) {

                        echo "<tr class='hover:bg-gray-50 transition-colors duration-200'>";
                        $columns = $model->columns();
                        foreach ($row as $key => $cell) {
                            $options = $columns[$key]["select_options"] ?? null;
                            if ($options) {
                                $cell = $options[$cell] ?? $cell;
                            }
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($cell) . "</td>";
                        }
                        if (!empty($actions)) {
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>";
                            foreach ($actions as $action => $data) {
                                echo "<a href='{$data[1]}/" . urlencode($row[$primary_key]) . "' class='text-blue-600 hover:text-blue-900 mr-2'>" . $data[0] . "</a>";
                            }
                            echo "</td>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <?php
                    if ($pages > 1) {
                        for ($i = 1; $i <= $pages; $i++) {
                            if ($i == $current_page) {
                                echo "<span class='relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600'>" . $i . "</span>";
                            } else {
                                echo "<a href='?page=" . $i . "' class='relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200'>" . $i . "</a>";
                            }
                        }
                    }
                    ?>
                </nav>
            </div>
        </div>
    </div>
<?php
    // require 'views/components/table.component.php';
}
