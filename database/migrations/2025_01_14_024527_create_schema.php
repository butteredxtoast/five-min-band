<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the schema if it doesn't exist
        DB::unprepared('CREATE SCHEMA IF NOT EXISTS five_min_band');
        
        // Grant privileges to the sail user
        DB::unprepared('GRANT ALL PRIVILEGES ON five_min_band.* TO \'sail\'@\'%\'');
        DB::unprepared('FLUSH PRIVILEGES');
        
        // Set the schema as default for this connection
        DB::unprepared('USE five_min_band');

        // Create migrations table in the new schema
        DB::unprepared('
            CREATE TABLE IF NOT EXISTS five_min_band.migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the migrations table first
        DB::unprepared('DROP TABLE IF EXISTS five_min_band.migrations');
        
        // Revoke privileges from the sail user
        DB::unprepared('REVOKE ALL PRIVILEGES ON five_min_band.* FROM \'sail\'@\'%\'');
        DB::unprepared('FLUSH PRIVILEGES');
        
        // Drop the schema if it exists
        DB::unprepared('DROP SCHEMA IF EXISTS five_min_band');
    }
};
