<?php
// index.php - Simple PHP home page
session_start();

// Basic helpers
function e($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

// Handle contact form submission
$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    $token = $_POST['csrf'] ?? '';
    $name  = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $msg   = trim((string)($_POST['message'] ?? ''));

    if (!hash_equals(csrf_token(), $token)) {
        $flash = 'Invalid form submission. Please try again.';
    } elseif ($msg === '' || strlen($msg) < 5) {
        $flash = 'Please provide a message (at least 5 characters).';
    } else {
        // In a real app you'd send an email or persist the message.
        $flash = 'Thanks, ' . e($name ?: 'Guest') . '. Your message was received.';
        // clear message to avoid resubmission
        $_POST = [];
    }
}

// Simple router
$page = $_GET['page'] ?? 'home';
$pages = ['home', 'about', 'contact'];
if (!in_array($page, $pages, true)) {
    $page = 'home';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo e(ucfirst($page)); ?> - My PHP Site</title>
<style>
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; margin: 0; padding: 0; color: #222; }
    header { background:#0b72b9; color:#fff; padding:1rem; }
    nav a { color:#fff; margin-right:1rem; text-decoration:none; }
    main { padding:1rem; max-width:900px; margin:0 auto; }
    footer { padding:1rem; text-align:center; color:#666; font-size:.9rem; }
    .card { background:#fff; padding:1rem; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,.08); }
    .flash { margin-bottom:.75rem; padding:.5rem .75rem; background:#eef; border:1px solid #cde; border-radius:4px; }
    input, textarea { width:100%; padding:.5rem; margin:.4rem 0 1rem 0; border:1px solid #ddd; border-radius:4px; }
    button { background:#0b72b9; color:#fff; border:none; padding:.6rem 1rem; border-radius:4px; cursor:pointer; }
</style>
</head>
<body>
<header>
    <div style="max-width:900px;margin:0 auto;">
        <h1 style="margin:0;font-size:1.25rem;">My PHP Site</h1>
        <nav>
            <a href="?page=home">Home</a>
            <a href="?page=about">About</a>
            <a href="?page=contact">Contact</a>
        </nav>
    </div>
</header>

<main>
    <?php if ($flash): ?><div class="flash"><?php echo e($flash); ?></div><?php endif; ?>

    <?php if ($page === 'home'): ?>
        <section class="card">
            <h2>Welcome</h2>
            <p>This is a minimal PHP home page. Use the navigation to explore.</p>
            <p>Server time: <?php echo e(date('Y-m-d H:i:s')); ?></p>
        </section>

    <?php elseif ($page === 'about'): ?>
        <section class="card">
            <h2>About</h2>
            <p>Lightweight example site built with plain PHP. No frameworks required.</p>
        </section>

    <?php elseif ($page === 'contact'): ?>
        <section class="card">
            <h2>Contact</h2>
            <form method="post" action="?page=contact" novalidate>
                <input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="action" value="contact">
                <label>
                    Name
                    <input type="text" name="name" value="<?php echo e($_POST['name'] ?? ''); ?>">
                </label>
                <label>
                    Email
                    <input type="email" name="email" value="<?php echo e($_POST['email'] ?? ''); ?>">
                </label>
                <label>
                    Message
                    <textarea name="message" rows="5"><?php echo e($_POST['message'] ?? ''); ?></textarea>
                </label>
                <button type="submit">Send</button>
            </form>
        </section>
    <?php endif; ?>
</main>

<footer>
    <div class="card" style="display:inline-block;">
        &copy; <?php echo date('Y'); ?> My PHP Site
    </div>
</footer>
</body>
</html>