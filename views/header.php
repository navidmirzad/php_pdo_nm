<?php

$current_page = basename($_SERVER['SCRIPT_NAME'], '.php');

$page_titles = [
    'index' => 'Home',
    'departments' => 'Departments',
    'employees' => 'Employees',
    'projects' => 'Projects'
];

$header_title = $page_titles[$current_page] ?? 'Company';
$browser_title = $page_titles[$current_page ?? 'KEA - PHP - Company'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business - <?php echo htmlspecialchars($browser_title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="/public/business.png" type="image/png">

</head>
<body>

<header style="display: flex; justify-content: center;">
<h1><?php echo htmlspecialchars($header_title); ?></h1>
</header>
