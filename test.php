<?php

$desc = [["pipe", "r"], ["pipe", "w"], ["file", "./error.txt", "a"]];



$process = proc_open("./venv/bin/python3 fingerprint.py", $desc, $pipes);


if (is_resource($process)) {
    print_r(json_decode(stream_get_contents($pipes[1])));
    fclose($pipes[1]);
}

$rv = proc_close($process);
// echo "rv " . $rv;
