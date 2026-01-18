<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Store the old values first by creating new columns
            $table->unsignedBigInteger('document_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('document_status_id')->nullable()->after('document_type_id');
            $table->unsignedBigInteger('document_category_id')->nullable()->after('document_status_id');
            
            // Add foreign keys
            $table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('set null');
            $table->foreign('document_status_id')->references('id')->on('document_statuses')->onDelete('set null');
            $table->foreign('document_category_id')->references('id')->on('document_categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeignKey(['document_type_id']);
            $table->dropForeignKey(['document_status_id']);
            $table->dropForeignKey(['document_category_id']);
            $table->dropColumn(['document_type_id', 'document_status_id', 'document_category_id']);
        });
    }
};
