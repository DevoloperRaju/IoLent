<?php
header('Content-Type: application/json');

// Register shutdown function to catch database connection errors
register_shutdown_function(function() {
    if (!isset($GLOBALS['conn']) || (isset($GLOBALS['conn']) && $GLOBALS['conn']->connect_error)) {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Database connection failed"]);
        exit;
    }
});

// Start output buffering to catch raw die message if connection fails
ob_start();
require_once "connection.html";
ob_end_clean();

// Receive email from POST request
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Email validation
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== '@gmail.com') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email"
    ]);
    return;
}

// Check if user exists in the database using userid and pin
$stmt = $conn->prepare("SELECT userid, pin FROM users WHERE email = ?");
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "something went wrong please try again"
    ]);
    return;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    return;
}

$user = $result->fetch_assoc();
$userid = $user['userid'];
$pin = $user['pin'];
$stmt->close();

// Secure token generation: minimum 16 characters
try {
    $login_token = bin2hex(random_bytes(16)); // 32 characters cryptographically secure hex
} catch (Exception $e) {
    $login_token = bin2hex(openssl_random_pseudo_bytes(16));
}

// Token expire system: 7 days in milliseconds
$current_time_ms = round(microtime(true) * 1000);
$seven_days_ms = 7 * 24 * 60 * 60 * 1000;
$token_expire = $current_time_ms + $seven_days_ms;

// Convert to string to prevent large integer precision loss in queries/bindings
$token_expire_str = (string)$token_expire;

// Database update
$update_stmt = $conn->prepare("UPDATE users SET login_token = ?, token_expire = ? WHERE email = ?");
if (!$update_stmt) {
    $conn->close();
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update token"
    ]);
    return;
}
$update_stmt->bind_param("sss", $login_token, $token_expire_str, $email);

if (!$update_stmt->execute()) {
    $update_stmt->close();
    $conn->close();
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update token"
    ]);
    return;
}

$update_stmt->close();
$conn->close();

// Check if PIN is not set or is empty or 0
$is_new = ($pin === null || $pin === '' || $pin === 0 || $pin === '0');

if ($is_new) {
    echo json_encode([
        "status" => "success",
        "type" => "new_user",
        "redirect" => "PinSetup.html",
        "login_token" => $login_token,
        "token_expire" => $token_expire_str,
        "userid" => (string)$userid
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "type" => "existing_user",
        "redirect" => "PinVerify.html",
        "login_token" => $login_token,
        "token_expire" => $token_expire_str,
        "userid" => (string)$userid
    ]);
}
return;
?>
