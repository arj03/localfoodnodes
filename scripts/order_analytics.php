<?php
mb_internal_encoding("UTF-8");
$server = '192.168.10.10';
$user = 'homestead';
$password = 'secret';
$database = 'localfoodnodes';

$db = new mysqli($server, $user, $password, $database);
// $db->set_charset('utf8');

$orders = $db->query("
    SELECT * FROM order_date_item_links AS odil
    LEFT JOIN order_dates AS od ON odil.order_date_id = od.id
    LEFT JOIN order_items AS oi ON odil.order_item_id = oi.id
");

$file = fopen("/home/davidajnered/Sites/orders.csv", "wr") or die("Unable to open file!");
$writeHeaders = true;

if ($orders->num_rows > 0) {
    while ($order = $orders->fetch_assoc()) {
        $order = flatten($order, 'user', ['id', 'name']);
        $order = flatten($order, 'node', ['id', 'name']);
        $order = flatten($order, 'producer', ['id', 'name']);
        $order = flatten($order, 'product', ['id', 'name', 'price']);
        $order = flatten($order, 'variant', ['id', 'name', 'price']);

        unset($order['product_is_hidden']);
        unset($order['message']);

        // Write headers
        if ($writeHeaders) {
            fputcsv($file, array_keys($order));
            $writeHeaders = false;
        }

        // Writing
        fputcsv($file, $order);
    }
}

fclose($file);
$db->close();

// Helper
function flatten($data, $key, $subKeys) {
    $keyData = json_decode($data[$key], true);

    foreach ($subKeys as $subKey) {
        $data[$key . '_' . $subKey] = isset($keyData[$subKey]) ? $keyData[$subKey] : null;
    }

    unset($data[$key]);

    return $data;
}
