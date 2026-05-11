<?php

$url = "https://smartgateway.hdfcuat.bank.in/session";

$merchant_id = "SG4997";
$api_key = "42C37827720405787028BC509FD473";
$customer_id = "hdfcmaster";

// ✅ IMPORTANT: only API key (as per doc)
$auth = base64_encode($api_key);

$payload = json_encode([
    "order_id" => "testing-order-" . time(),
    "amount" => "10.0",
    "customer_id" => $customer_id,
    "customer_email" => "test@mail.com",
    "customer_phone" => "9876543210",
    "payment_page_client_id" => "hdfcmaster",
    "action" => "paymentPage",
    "currency" => "INR",
    "return_url" => "http://localhost/jshb/response.php",
    "description" => "Complete your payment",
    "first_name" => "John",
    "last_name" => "wick"
]);

$headers = [
    "Authorization: Basic " . $auth,
    "Content-Type: application/json",
    "x-merchantid: " . $merchant_id,
    "x-customerid: " . $customer_id
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("Curl error: " . curl_error($ch));
}

curl_close($ch);

$result = json_decode($response, true);

echo "<pre>";
print_r($result);
echo "</pre>";

if (!empty($result['payment_links']['web'])) {
    header("Location: " . $result['payment_links']['web']);
    exit;
}
?>