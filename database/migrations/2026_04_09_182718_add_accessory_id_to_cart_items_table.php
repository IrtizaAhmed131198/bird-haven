<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // InnoDB ghost FK entries from other databases on this server block index drops.
        // Temporarily disable FK checks to allow the unique index drop.
        DB::statement('SET foreign_key_checks = 0');
        DB::statement('ALTER TABLE cart_items DROP INDEX cart_items_user_id_bird_id_unique');
        DB::statement('ALTER TABLE cart_items MODIFY bird_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE cart_items ADD COLUMN accessory_id BIGINT UNSIGNED NULL AFTER bird_id');
        DB::statement('ALTER TABLE cart_items ADD CONSTRAINT cart_items_accessory_id_foreign FOREIGN KEY (accessory_id) REFERENCES accessories(id) ON DELETE SET NULL');
        DB::statement('SET foreign_key_checks = 1');
    }

    public function down(): void
    {
        DB::statement('SET foreign_key_checks = 0');
        DB::statement('ALTER TABLE cart_items DROP FOREIGN KEY cart_items_accessory_id_foreign');
        DB::statement('ALTER TABLE cart_items DROP COLUMN accessory_id');
        DB::statement('ALTER TABLE cart_items MODIFY bird_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE cart_items ADD UNIQUE KEY cart_items_user_id_bird_id_unique (user_id, bird_id)');
        DB::statement('SET foreign_key_checks = 1');
    }
};
