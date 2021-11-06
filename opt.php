<?php
require 'conf.php';

/* 
 * Copyright (C) 2021 WauHundeland
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
if (!isset($_SESSION['userid'])) { // Überprüfe Login
    die('Bitte zuerst <a href="login.php">anmelden</a>'); // wenn nein, sende Fehlermeldung
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body {
                font-family: sans-serif;
            }
            .sidebaritem {
                display: block; margin-left: auto; margin-right: auto; text-align: center; color: white; background: #3f4b4f; 
            }
            .sidebaritem:hover{
                background: lightblue; 
                color: black;
            }
        </style>
        <script>
            function updateClipboard(newClip) {
                navigator.clipboard.writeText(newClip).then(function () {
                    /* clipboard successfully set */
                }, function () {
                    /* clipboard write failed */
                });
            }

        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
    </head>
    <body style="margin: auto;">
        <div style="margin: 0; padding: 0; position: absolute; top: 0; left: 0; width: 5em; height: 100%; background: #2f3a3f;">
            <div style="display: block; margin-left: auto; margin-right: auto; text-align: center;">
                <img src="logo.png" style="width: 3em; height: 3em;" alt="ec_logo"/>
            </div>
            <hr>
            <a href="index.php">
                <div class="sidebaritem">
                    <img src="vrack.png" style="width: 3em; height: 3em; display: block; margin-left: auto; margin-right: auto;" alt="VPS"/>
                    <small style="text-align: center">VMs</small>
                </div></a>
        </div>
        <div style="margin-left: 5.5em; overflow-x: hidden; overflow-y: hidden; margin-left: 5.5em; height: 100%;">
            <?php
            $id = $_SESSION["userid"];
            $statement = $pdo->prepare("SELECT * FROM vm WHERE userid = ? AND name = ?");
            $statement->execute(array($id, $_GET["name"]));
            while ($roww = $statement->fetch()) {
                exec('"C:\Program Files\Oracle\VirtualBox\VBoxManage.exe" showvminfo "' . $roww["name"] . '" | findstr /c:"Memory size: "', $ram);
                exec('"C:\Program Files\Oracle\VirtualBox\VBoxManage.exe" showvminfo "' . $roww["name"] . '" | findstr /c:"VRAM size: "', $vram);
                exec('"C:\Program Files\Oracle\VirtualBox\VBoxManage.exe" showvminfo "' . $roww["name"] . '" | findstr /c:"TCP/Ports"', $port);
                exec('"C:\Program Files\Oracle\VirtualBox\VBoxManage.exe" showvminfo "' . $roww["name"], $stck);
                echo '<div class="ec_group" style="text-align: center; margin-bottom: 3px;"><span style="font-size: 2em; font-weight: bold">' . $roww["name"] . "</span><br>RAM-Größe: " . ltrim($ram[0], "Memory size: ") . "<br>Videospeichergröße: " . ltrim($vram[0], "VRAM size: ") . "<br>IP: <code>$ip:" . rtrim(ltrim($port[0], 'VRDE property : TCP/Ports = "'), '"') . '</code>' . "<br>Batch-Befehl: <span onclick='updateClipboard(\"mstsc /v $ip:" . rtrim(ltrim($port[0], 'VRDE property : TCP/Ports = "'), '"') . " /f\")'><code>mstsc /v $ip:" . rtrim(ltrim($port[0], 'VRDE property : TCP/Ports = "'), '"') . ' /f</code></span><br>';
                exec('"C:\Program Files\Oracle\VirtualBox\VBoxManage.exe" showvminfo "' . $roww["name"] . '" | findstr /c:"running (since"', $output);
                if ($output == []) {
                    echo '<a href="boot.php?vm=' . $roww["name"] . '"><button onclick="this.innerHTML = \'Bitte warten\'">Bootvorgang starten</button></a><br />';
                }
                if ($output !== []) {
                    echo '<a href="stop.php?vm=' . $roww["name"] . '"><button onclick="this.innerHTML = \'Bitte warten\'">Stoppen</button></a><br />';
                }
                echo '<br><br><strong>Debugging</strong><br><br>Eigenschaften: <br><br><div style="margin-left: 3em; margin-right: 3em;height: 20em; overflow-y: scroll; text-align: left; background: light-grey;"><pre><code>';
                foreach ($stck as $value) {
                    echo $value . "<br>";
                }
                echo '</code></pre></div><br><br><strong>ISO einlegen</strong><br><br>';

                echo '<form method="POST" enctype="multipart/form-data"><input type="file" name="isofile" /><input type="submit" value="Einlegen" /></form>';
                echo '</div>';
                if (isset($_FILES["isofile"])) {
                    $structure = $isofolder."\\".$roww["name"];
                    if (!is_dir($structure)) {
                        if (!mkdir($structure, 0777, true)) {
                            die('Failed to create directories...');
                        }
                    }
                    move_uploaded_file($_FILES["isofile"]["tmp_name"], $isofolder . $roww["name"] . "\\" . rtrim($_FILES["isofile"]["name"], ".iso") . ".iso");
                    exec('"C:\Program Files\Oracle\VirtualBox\VBoxManage.exe" storageattach "' . $roww["name"] . '" --storagectl IDE --type dvddrive --port 0 --device 0 --medium "' . $isofolder . $roww["name"] . "\\" . rtrim($_FILES["isofile"]["name"], ".iso") . ".iso" . '"', $stck);
                }
            }
            ?>

        </div>
    </body>
</html>
