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
        Schema::table('loans', function (Blueprint $table) {
            $table->index(['created_at']);
            $table->index(['loan_date']);
            $table->index(['due_date']);
            $table->index(['returned_at']);
            $table->index(['user_id']);
            $table->index(['book_id']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->index(['genre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['loan_date']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['returned_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['book_id']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['genre']);
        });
    }
};
