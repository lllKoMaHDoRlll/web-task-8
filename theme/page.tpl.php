<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/page.css">
    <script src="/scripts/script.js" defer></script>
</head>


<?php
foreach ($c['#content'] as $content) {
  echo $content;
}
?>