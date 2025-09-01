<?php
session_start();
header('Content-Type: application/json');
include '../../../db.php';
include '../../db.php';

if (!function_exists('aes_encrypt')) {
function aes_encrypt(
    $plaintext,
    $key
) {
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
    return base64_encode($iv . $hmac . $ciphertext_raw);
}
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method is allowed"]);
    exit;
}

$u = $_GET['unique'] ?? null;

if (!$u) {
    http_response_code(400);
    echo json_encode(["error" => "Unique ID is required"]);
    exit;
}

$q = $pdo2->prepare("SELECT * FROM webhooks WHERE unique_id = ?");
$q->execute([$u]);
$w = $q->fetch(PDO::FETCH_ASSOC);

if (!$w) {
    http_response_code(404);
    echo json_encode(["error" => "Webhook not found"]);
    exit;
}

if ($w['disabled'] === 'TRUE') {
    http_response_code(403);
    echo json_encode(["error" => "This webhook is disabled"]);
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'];
$ip_enc = aes_encrypt((string)$ip, $_ENV['IP_AES_KEY']);

function f($a, $b) {
    $b = trim($b);
    if ($b === '' || strpos($b, '/') === false) {
        return false;
    }
    list($c, $n) = explode('/', $b, 2);
    $x = ip2long($a);
    $y = ip2long($c);
    $n = (int)$n;
    if ($x === false || $y === false || $n < 0 || $n > 32) {
        return false;
    }
    $m = -1 << (32 - $n);

    return ($x & $m) === ($y & $m);
}

$ips = array_filter(array_map('trim', explode(',', (string)$w['allowed_ips'])));
$ok = in_array("0.0.0.0", $ips, true) || in_array($ip, $ips, true);

$ok2 = false;
$c = !empty($w['allowed_companys']) ? array_filter(array_map('trim', explode(',', $w['allowed_companys']))) : [];

if (!$ok && !empty($c)) {
    $p1 = implode(',', array_fill(0, count($c), '?'));
    $q = $pdo2->prepare("SELECT ip_range FROM company_ips WHERE company_name IN ($p1)");
    $q->execute($c);
    $r = $q->fetchAll(PDO::FETCH_COLUMN);

    foreach ($r as $t) {
        if (f($ip, $t)) {
            $ok2 = true;
            break;
        }
    }
}

if (!$ok && !$ok2) {
    http_response_code(403);
    echo json_encode(["error" => "IP not allowed"]);
    exit;
}

$rl = (int)$w['rate_limit'];
$wid = $w['id'];

if ($rl > 0) {
    $q = $pdo2->prepare("SELECT last_request FROM webhook_requests WHERE webhook_id = ? AND ip_address = ? ORDER BY last_request DESC LIMIT 1");
    $q->execute([$wid, $ip_enc]);
    $lr = $q->fetch(PDO::FETCH_ASSOC);

    $now = time();

    if ($lr) {
        $lrt = strtotime($lr['last_request']);
        $dt = $now - $lrt;

        if ($dt < $rl) {
            $q = $pdo2->prepare("UPDATE webhook_requests SET last_request = NOW() WHERE webhook_id = ? AND ip_address = ?");
            $q->execute([$wid, $ip_enc]);

            http_response_code(429);
            echo json_encode(["error" => "Rate limit exceeded, please try again later"]);
            exit;
        }
    }

    $q = $pdo2->prepare("INSERT INTO webhook_requests (webhook_id, ip_address, last_request) VALUES (?, ?, NOW())");
    $q->execute([$wid, $ip_enc]);
}

$raw = file_get_contents('php://input');
$j = json_decode($raw, true);
if ($raw !== '' && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON payload"]);
    exit;
}

if ($w['webhook_platform'] === 'Discord') {
    $url = str_replace("discord.com", "webhook.lewisakura.moe", $w['webhook_url']);

    $msg = isset($j['content']) && is_string($j['content']) && $j['content'] !== ''
        ? $j['content']
        : 'Default message';
    
    $pl = [
        "content" => $msg
    ];

    if (isset($j['username']) && is_string($j['username']) && $j['username'] !== '') {
        $pl['username'] = $j['username'];
    }

    if (isset($j['avatar_url']) && is_string($j['avatar_url']) && $j['avatar_url'] !== '') {
        $pl['avatar_url'] = $j['avatar_url'];
    }

    $pl = json_encode($pl);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 204) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to send webhook", "response" => $resp]);
        exit;
    }

    echo json_encode(["success" => "Webhook sent successfully"]);
    exit;
}

http_response_code(400);
echo json_encode(["error" => "Unsupported webhook platform"]);
?>