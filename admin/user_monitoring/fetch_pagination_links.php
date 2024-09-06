<?php
// Retrieve current page from query string
$current = isset($_GET['current']) ? (int)$_GET['current'] : 1;
$totalPages = 1000000000000000000; // Example value, replace it with your actual total pages count
$showPages = 5; // Adjust this number as needed for your pagination UI

// Generate pagination links
$pagination = '';

// Previous page link
if ($current > 1) {
    $pagination .= '<a href="?current=' . ($current - 1) . '" class="arrow" data-page="' . ($current - 1) . '">&lsaquo;</a>';
}

// Page numbers
$start = max(1, $current - floor($showPages / 2));
$end = min($start + $showPages - 1, $totalPages);

for ($i = $start; $i <= $end; $i++) {
    $active = ($current == $i) ? 'active' : '';
    $pagination .= '<a href="?current=' . $i . '" class="' . $active . '" data-page="' . $i . '">' . $i . '</a>';
}

// Next page link
if ($current < $totalPages) {
    $pagination .= '<a href="?current=' . ($current + 1) . '" class="arrow" data-page="' . ($current + 1) . '">&rsaquo;</a>';
}

// Output pagination links
echo $pagination;
?>