<?php
require '../config.php';
require '../helpers.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$date = $_GET['date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'date must use YYYY-MM-DD format']);
    exit;
}

$stmt = $conn->prepare('
    SELECT v.id, v.vendor_name, v.category, v.location, v.price_per_unit,
           v.unit_label, v.capacity, COUNT(b.id) AS booked
    FROM vendors v
    LEFT JOIN bookings b
      ON b.vendor_id = v.id
     AND b.booking_date = ?
    GROUP BY v.id, v.vendor_name, v.category, v.location,
             v.price_per_unit, v.unit_label, v.capacity
    ORDER BY v.vendor_name
');
$stmt->bind_param('s', $date);
$stmt->execute();
$vendors = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($vendors as &$vendor) {
    $vendor['id'] = (int)$vendor['id'];
    $vendor['price_per_unit'] = (float)$vendor['price_per_unit'];
    $vendor['capacity'] = (int)$vendor['capacity'];
    $vendor['booked_today'] = (int)$vendor['booked'];
    unset($vendor['booked']);
}
unset($vendor);

echo json_encode([
    'date' => $date,
    'count' => count($vendors),
    'vendors' => $vendors,
], JSON_UNESCAPED_SLASHES);
