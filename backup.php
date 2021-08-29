<?php

if (!isset($_GET["log"])) {
    header("Location: index.php");
    exit(0);
}

$knownGood = ["backup", "local-rsync", "local-rsync-error", "remote-rsync", "remote-rsync-error"];
$log = $_GET["log"];
header("Content-Type: text/plain");

if (!in_array($log, $knownGood, TRUE)) {
    header("Location: index.php");
    exit(0);
}

echo file_get_contents("/home/backup/$log.log");
