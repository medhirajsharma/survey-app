<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->string('mobile_no')->nullable()->after('name');
            $table->foreignId('vidhansabha_id')->nullable()->constrained()->after('mobile_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropForeign(['vidhansabha_id']);
            $table->dropColumn(['mobile_no', 'vidhansabha_id']);
        });
    }
};
