<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birds', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('featured');
            $table->index(['is_active', 'featured']);
            $table->index(['is_active', 'created_at']);
            $table->index('color');
            $table->index('price');
            $table->index('stock');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_status');
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('approved');
            $table->index(['bird_id', 'approved']);
            $table->index('created_at');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('type');
            $table->index(['is_active', 'type']);
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->index('stage');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::table('birds', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['is_active', 'featured']);
            $table->dropIndex(['is_active', 'created_at']);
            $table->dropIndex(['color']);
            $table->dropIndex(['price']);
            $table->dropIndex(['stock']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['approved']);
            $table->dropIndex(['bird_id', 'approved']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['type']);
            $table->dropIndex(['is_active', 'type']);
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex(['stage']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['is_read']);
        });
    }
};
