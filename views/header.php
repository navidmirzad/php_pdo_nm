<?php

$current_page = basename($_SERVER['SCRIPT_NAME'], '.php');

$page_titles = [
    'index' => 'Company',
    'departments' => 'Departments',
    'employees' => 'Employees',
    'projects' => 'Projects'
];

$header_title = $page_titles[$current_page] ?? 'Company';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP KEA Development</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header style="display: flex; justify-content: center;">
<h1><?php echo htmlspecialchars($header_title); ?></h1>
</header>
