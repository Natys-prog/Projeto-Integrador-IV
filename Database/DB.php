<?php
// File: Database.php
// Simple PDO-based MySQL integration for a PHP web application.
// Place this file in /Database/DB/ and include or require it where needed.

class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    // Provide DSN parts here or via environment variables
    public function __construct(array $config = [])
    {
        $host = $config['host'] ?? getenv('DB_HOST') ?: '127.0.0.1';
        $port = $config['port'] ?? getenv('DB_PORT') ?: '3308';
        $db   = $config['dbname'] ?? getenv('DB_NAME') ?: 'my_database';
        $user = $config['user'] ?? getenv('DB_USER') ?: 'root';
        $pass = $config['pass'] ?? getenv('DB_PASS') ?: '';
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new \PDO($dsn, $user, $pass, $options);
    }

    // Singleton accessor (optional)
    public static function getInstance(array $config = []): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database($config);
        }
        return self::$instance;
    }

    // Prepare & execute a statement with parameters, return PDOStatement
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fetch single row
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    // Fetch all rows
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Insert and return last insert id
    public function insert(string $sql, array $params = []): string
    {
        $this->query($sql, $params);
        return $this->pdo->lastInsertId();
    }

    // Execute an UPDATE/DELETE; returns affected rows
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    // Transaction helpers
    public function beginTransaction(): bool { return $this->pdo->beginTransaction(); }
    public function commit(): bool { return $this->pdo->commit(); }
    public function rollBack(): bool { return $this->pdo->rollBack(); }
}

/*
Usage examples:

// 1) Using environment variables (recommended) and singleton:
$db = Database::getInstance();

// 2) Or pass config array:
$db = Database::getInstance([
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => 'testdb',
    'user' => 'appuser',
    'pass' => 'secret',
]);

// SELECT one:
$user = $db->fetch('SELECT id, name, email FROM users WHERE id = :id', ['id' => 1]);

// SELECT many:
$rows = $db->fetchAll('SELECT id, name FROM users WHERE active = :act', ['act' => 1]);

// INSERT:
$newId = $db->insert('INSERT INTO users (name, email) VALUES (:name, :email)', [
    'name' => 'Alice',
    'email' => 'alice@example.com'
]);

// UPDATE:
$affected = $db->execute('UPDATE users SET active = 0 WHERE last_login < :date', [
    'date' => '2024-01-01'
]);

// Transactions:
try {
    $db->beginTransaction();
    $db->execute('UPDATE accounts SET balance = balance - :amt WHERE id = :from', ['amt'=>100, 'from'=>1]);
    $db->execute('UPDATE accounts SET balance = balance + :amt WHERE id = :to', ['amt'=>100, 'to'=>2]);
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}

Security notes:
- Use prepared statements (shown) to avoid SQL injection.
- Prefer separate DB user with limited privileges.
- Store credentials in environment variables or a secure config, not in version control.

PHP requirements:
- PHP >= 7.4 (typed properties used) or remove types for older PHP.
- pdo_mysql extension enabled (php.ini: extension=pdo_mysql)
*/