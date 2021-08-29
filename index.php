<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="refresh" content="5" >
    <title>Picton</title>

    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <h1>Picton Server</h1>
    <table>
        <tr>
            <th>Operating System</th>
            <td><?php echo shell_exec("lsb_release -ds"); ?></td> <!-- lsb_release -ds -->
        </tr>
        <tr>
            <th>Kernel</th>
            <td><?php echo shell_exec("uname -sr"); ?></td> <!-- uname -sr -->
        </tr>
        <tr>
            <th>Uptime</th>
            <td><?php echo shell_exec("uptime -p"); ?></td> <!-- uptime -p -->
        </tr>
        <tr>
            <th>Memory</th>
            <td><?php echo getMemory(); ?></td> <!-- uptime -p -->
        </tr>
    </table>

    <h2 id="shares">Shares</h2>
    <p>Users can access their shares through SMB or SFTP.</p>
    <p>Public share: <a href="public">HTTP</a> &middot; <a href="smb://192.168.1.5/Public">SMB</a> &middot; <a href="sftp://192.168.1.5/stor1/shares/public">SFTP</a></p>

    <h2 id="backup">Backup</h2>
    <table>
        <tr>
            <th>Backup Running?</th>
            <td><?php echo isBackupRunning(); ?></td>
        </tr>
        <tr>
            <th>Last Successful Backup</th>
            <td><?php echo lastSuccessfulBackup(); ?></td>
        </tr>
        <tr>
            <th>Last Failed Backup</th>
            <td><?php echo lastFailedBackup(); ?></td>
        </tr>
    </table>
    <p>
      <a href="backup.php?log=backup">View log</a>
      &middot;
      <a href="backup.php?log=local-rsync">Local</a> (<a href="backup.php?log=local-rsync-error">Error</a>) &middot;
      <a href="backup.php?log=remote-rsync">Remote</a> (<a href="backup.php?log=remote-rsync-error">Error</a>)
    </p>
  </body>
</html>

<?php

function getMemory() {
    $input = explode("\n" ,shell_exec("free --si -h"))[1];
    $values = preg_split('/\s+/', $input);

    $total = $values[1] . "B";
    $used = $values[2] . "B";
    $free = $values[6] . "B"; // uses available instead of free

    return "$used / $total ($free free)";
}

function isBackupRunning() {
    $date = file_get_contents("/home/backup/.running");
    if ($date) {
        return "✅ Yes ($date)";
    }

    return "❌ No";
}

function lastSuccessfulBackup() {
    $date = file_get_contents("/home/backup/.last");
    if ($date) {
        return "✅ Yes ($date)";
    }

    return "❌ No";
}

function lastFailedBackup() {
    $date = file_get_contents("/home/backup/.failed");
    if ($date) {
        return "$date";
    }

    return "❌ No";
}

?>
