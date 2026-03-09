<?php 

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/functions.php';

$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = 1"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Personal DevHub</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./../assets/css/output.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
</head>

<body class="font-sans">
    <!-- NAV -->
    <nav class="border-b border-border px-6 py-4 flex items-center justify-between max-w-5xl mx-auto">
        <span class="font-mono text-accent font-semibold tracking-tight">{'Personal DevHub'}</span>
        <div class="flex gap-6 text-sm text-dim">
            <a href="/index.php" class="hover:text-text transition-colors">Home</a>
            <a href="/views/projects.php" class="hover:text-text transition-colors">Projects</a>
            <a href="#snippets" class="hover:text-text transition-colors">Snippets</a>
            <a href="#stack" class="hover:text-text transition-colors">Stack</a>
        </div>
    </nav>