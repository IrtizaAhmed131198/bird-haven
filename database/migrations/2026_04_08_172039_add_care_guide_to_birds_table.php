<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('birds', function (Blueprint $table) {
            $table->text('habitat_guide')->nullable()->after('habitat');
            $table->text('nutrition_guide')->nullable()->after('nutrition');
            $table->text('social_guide')->nullable()->after('social');
        });
    }

    public function down(): void
    {
        Schema::table('birds', function (Blueprint $table) {
            $table->dropColumn(['habitat_guide', 'nutrition_guide', 'social_guide']);
        });
    }
};
