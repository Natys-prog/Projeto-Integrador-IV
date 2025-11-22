<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DatabaseInitializationService
{
    public function createDatabase()
    {
        try {
            // Run Laravel migrations
            \Artisan::call('migrate', ['--force' => true]);
            
            // Run seeders to populate data
            \Artisan::call('db:seed', ['--force' => true]);
            
            Log::info('Database initialized successfully with EPIs and Funcionários data');
            return [
                'success' => true, 
                'message' => 'Database initialized successfully with EPIs and Funcionários data. 
                             You can now see real data in the dashboard!'
            ];
            
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