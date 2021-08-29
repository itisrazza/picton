<?php

if (!isset($_GET["log"])) {
    header("Location: index.php");
    exit(0);
}

$knownGood = ["backup", "local-rsync", "local-rsync-error", "remote-rsync", "remote-rsync-error"];
$log = $_GET["log"];

if (!in_array($log, $knownGood, TRUE)) {
    header("Location: index.php");
    exit(0);
}

$file = file_get_contents("/home/backup/$log.log");
if ($file) {
    header("Content-Type: text/plain");
    header("Refresh: 2");
    echo $file;
    exit(0);
}

http_response_code(404);
echo "404 Not Found";
