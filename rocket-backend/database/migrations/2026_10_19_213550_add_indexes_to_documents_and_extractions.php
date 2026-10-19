<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint as MongoBlueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (MongoBlueprint $table) {
            $table->index('status');
        });
        Schema::table('extractions', function (MongoBlueprint $table) {
            $table->index('document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (MongoBlueprint $table) {
            $table->dropIndex(['status']);
        });
        Schema::table('extractions', function (MongoBlueprint $table) { 
            $table->dropIndex(['document_id']);
        });
    }
};
