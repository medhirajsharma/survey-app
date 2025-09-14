<?php

use App\Models\Survey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill UUIDs for existing surveys that don't have one
        Survey::whereNull('uuid')->each(function (Survey $survey) {
            $survey->uuid = (string) Str::uuid();
            $survey->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // In the down method, we can choose to set UUIDs back to null
        // or do nothing, depending on desired behavior. For now, we'll do nothing.
        // If you want to revert the UUIDs, you would do something like:
        // Survey::whereNotNull('uuid')->update(['uuid' => null]);
    }
};