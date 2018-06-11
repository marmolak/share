<?php

function exit_error_msg($msg = ":)")
{
    echo "{$msg}";
    exit(1);
}

$share_id = $_GET['id'];

if (empty($share_id)) {
    exit_error_msg();
}

$fp = fopen("./fss/{$share_id}", 'r');
if (!$fp) {
    exit_error_msg("::)");
}

list($shared_dir, $file_name)  = fgetcsv($fp, 1024, ",");
fclose($fp);

chdir($shared_dir);
$zipfilename = "{$file_name}.zip";

$fp = popen('nice -n 19 ionice -c 3 zip -r -0 - ./', 'r');
if (!$fp) {
    echo "fuk you";
    exit(1);
}

header('Pragma: no-cache');
header('Content-Description: File Download');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header("Content-Disposition: attachment; filename=\"$zipfilename\"");

flush(); //Flushing the butter, pre streaming
while(!feof($fp)) {
    echo fread($fp, 8192);
}
pclose($fp);
?>
