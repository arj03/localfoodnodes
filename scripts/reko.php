<?php
$server = '192.168.99.100';
$user = 'root';
$password = 'root';
$database = 'localfoodnodes';

$db = new mysqli($server, $user, $password, $database);
$db->set_charset('utf8');

$statement = $db->prepare("INSERT INTO reko (name, link, location) VALUES (?, ?, GeomFromText(?));");

$row = 1;
if (($handle = fopen(getcwd() . '/reko-geo.csv', 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        list($lng, $lat) = explode(' ', $data[2]);
        $geo = 'Point(' . $lat . ' ' . $lng . ')';
        $statement->bind_param('sss', $data[0], $data[1], $geo);
        $statement->execute();
    }

    fclose($handle);
}

$statement->close();
$db->close();
