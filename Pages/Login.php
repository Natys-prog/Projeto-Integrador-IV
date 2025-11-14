<?php
session_start();

/*
  Simple, secure login page (PHP + PDO).
  - Edit the DB credentials below to match your environment.
  - Expects a `users` table with columns: id (INT PK), username (VARCHAR UNIQUE), password_hash (VARCHAR).
  - Passwords must be stored using password_hash(...).
*/
require_once __DIR__ . '/../Database/DB.php';

// Helper: connect to DB (adjust credentials above)
function getPDO($dsn, $dbUser, $dbPass) {
    try {
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // In production, log error and show generic message
        die('Database connection error.');
    }
}

// Generate CSRF token for the form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic CSRF check
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid request.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $errors[] = 'Username and password are required.';
        } else {
            $pdo = getPDO($dsn, $dbUser, $dbPass);
            $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                // Redirect to protected page - change path as needed
                header('Location: ../Index.php');
                exit;
            } else {
                $errors[] = 'Invalid username or password.';
            }
        }
    }
}

function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; background:#f6f8fa; display:flex; align-items:center; justify-content:center; height:100vh; }
        .card { background: #fff; padding: 24px; border-radius:8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); width:320px; }
        input[type="text"], input[type="password"] { width:100%; padding:8px; margin:6px 0 12px; box-sizing:border-box; }
        button { width:100%; padding:10px; background:#0366d6; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        .errors { color:#a94442; margin-bottom:12px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $err): ?>
                    <div><?php echo e($err); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token']); ?>">

            <label for="username">Username</label>
            <input id="username" name="username" type="text" autocomplete="username" value="<?php echo e($_POST['username'] ?? ''); ?>">

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password">

            <button type="submit">Sign in</button>
        </form>
    </div>
</body>
</html>