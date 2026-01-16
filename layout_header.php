<?php
// This will be our new main layout header for the mobile-first design.
// It includes a top app bar and placeholders for page titles.
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no">
    <title><?php echo $page_title ?? 'EIMBox'; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Layout & Theme Stylesheets -->
    <link rel="stylesheet" href="assets/layout.css">
    <link rel="stylesheet" href="assets/material_dashboard.css">

</head>
<body>

<div class="scrim" id="scrim"></div>

<nav class="navbar fixed-top app-bar">
    <div class="container-fluid">
        <button class="btn border-0" type="button" id="menu-toggle-btn">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>
        <span class="app-bar-title"><?php echo $page_title ?? 'Dashboard'; ?></span>
        <button class="btn border-0">
            <i class="bi bi-bell" style="font-size: 1.25rem;"></i>
        </button>
    </div>
</nav>

<main class="container-fluid mt-4">
    <div class="main-content">
