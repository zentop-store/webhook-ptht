<?php
// Ambil URL Webhook Discord dan Secret Token dari environment variables
$discordWebhookUrl = getenv('DISCORD_WEBHOOK_URL');
$secretToken = getenv('SECRET_TOKEN');

// Verifikasi token dari query parameter atau header
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    http_response_code(403);  // Respon 403 Forbidden jika token salah
    echo 'Forbidden: Invalid token';
    exit;
}

// Jika token valid, proses webhook
$statusMessage = isset($_POST['status']) ? $_POST['status'] : '';

if ($statusMessage) {
    $payload = json_encode(["content" => $statusMessage]);

    $ch = curl_init($discordWebhookUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_exec($ch);
    curl_close($ch);

    echo 'Webhook sent to Discord';
} else {
    echo 'No status message received.';
}
?>
