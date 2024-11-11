<?php
include('db_connect.php');

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

function execute_sql_file($conn, $filename) {
    $templine = ''; 
    $lines = file($filename); 
    foreach ($lines as $line) { 
        if (substr($line, 0, 2) == '--' || $line == '') 
            continue; 
        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';') { 
            if (!$conn->query($templine)) {
                echo 'Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />';
            }
            $templine = ''; 
        }
    }
}

execute_sql_file($conn, $dumpFile);

echo "Database loaded successfully.";

$conn->close();
?>