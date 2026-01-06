<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_share_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_share_id')
                  ->constrained('file_shares')
                  ->cascadeOnDelete();
            $table->foreignId('uploaded_file_id')
                  ->constrained('uploaded_files')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['file_share_id', 'uploaded_file_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_share_items');
    }
};
