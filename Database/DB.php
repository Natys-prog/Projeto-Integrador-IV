<?php
// File: Database.php
// Simple PDO-based MySQL integration for a PHP web application.
// Place this file in /Database/DB/ and include or require it where needed.

class Database
{
    private static ?Database $instance = null;
    private ?\PDO $pdo = null;
    private array $config;

    public function __construct(array $config = [])
    {
        // defaults — ajuste conforme necessário
        $this->config = array_merge([
            'host'    => '127.0.0.1',
            'port'    => '3308',
            'dbname'  => 'linke_homolog',
            'user'    => 'root',
            'pass'    => 'root',
            'charset' => 'utf8mb4',
        ], $config);
        // não cria PDO aqui (lazy connect)
    }

    private function getPDO(): \PDO
    {
        if ($this->pdo instanceof \PDO) {
            return $this->pdo;
        }

        $drivers = \PDO::getAvailableDrivers();
        if (empty($drivers)) {
            $ini = php_ini_loaded_file() ?: '(nenhum php.ini carregado)';
            throw new \RuntimeException("Nenhum driver PDO disponível. Edite php.ini ({$ini}) e habilite pdo_mysql ou pdo_sqlite.");
        }

        // Tentar MySQL se driver disponível e dbname configurado
        if (in_array('mysql', $drivers, true) && !empty($this->config['dbname'])) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $this->config['host'],
                $this->config['port'],
                $this->config['dbname'],
                $this->config['charset']
            );
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new \PDO($dsn, $this->config['user'], $this->config['pass'], $options);
            return $this->pdo;
        }

        // Fallback para SQLite (desenvolvimento)
        if (in_array('sqlite', $drivers, true)) {
            $sqliteFile = __DIR__ . '/../database.sqlite';
            $this->pdo = new \PDO('sqlite:' . $sqliteFile);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            return $this->pdo;
        }

        throw new \RuntimeException('Nenhum driver PDO utilizável encontrado: ' . implode(',', $drivers));
    }

    public function createDB(): void
    {
        $drivers = \PDO::getAvailableDrivers();

        if (in_array('mysql', $drivers, true)) {
            // Conectar sem DB para criar DB se necessário
            $tmpDsn = sprintf('mysql:host=%s;port=%s;charset=%s', $this->config['host'], $this->config['port'], $this->config['charset']);
            $tmpPdo = new \PDO($tmpDsn, $this->config['user'], $this->config['pass'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            $dbname = $this->config['dbname'];
            $tmpPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET {$this->config['charset']}");
            $tmpPdo->exec("USE `{$dbname}`");
            $tmpPdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
            );
            echo "MySQL: database e tabela 'users' garantidos.\n";
            return;
        }

        if (in_array('sqlite', $drivers, true)) {
            $pdo = $this->getPDO();
            $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL
);
SQL
            );
            echo "SQLite: arquivo e tabela 'users' garantidos.\n";
            return;
        }

        throw new \RuntimeException('createDB: nenhum driver PDO disponível.');
    }

    public static function getInstance(array $config = []): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database($config);
        }
        return self::$instance;
    }

    // Wrappers úteis
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert(string $sql, array $params = []): string
    {
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($params);
        return $this->getPDO()->lastInsertId();
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction(): bool { return $this->getPDO()->beginTransaction(); }
    public function commit(): bool { return $this->getPDO()->commit(); }
    public function rollBack(): bool { return $this->getPDO()->rollBack(); }
}
?>