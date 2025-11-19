<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseInitializationService
{
    public function createDatabase()
    {
        try {
            // You can adapt your existing DB.php logic here
            // This is a Laravel way to handle database operations
            
            // Example: Create tables if they don't exist
            $this->createUsersTable();
            $this->createContactsTable();
            
            Log::info('Database initialized successfully');
            return ['success' => true, 'message' => 'Database initialized successfully'];
            
        } catch (\Exception $e) {
            Log::error('Database initialization failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database initialization failed: ' . $e->getMessage()];
        }
    }
    
    private function createUsersTable()
    {
        if (!DB::select("SHOW TABLES LIKE 'users'")) {
            DB::statement("
                CREATE TABLE users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");
        }
    }
    
    private function createContactsTable()
    {
        if (!DB::select("SHOW TABLES LIKE 'contacts'")) {
            DB::statement("
                CREATE TABLE contacts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255),
                    email VARCHAR(255),
                    message TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");
        }
    }
}