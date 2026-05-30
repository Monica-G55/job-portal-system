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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_types_id')->constrained()->onDelete('cascade');
            $table->string('salary')->nullable();
            $table->string('vacancy');
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->text('benefits')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('keywords')->nullable();
            $table->text('responsibility')->nullable();
            $table->string('experience');
            $table->string('company_name');
            $table->string('company_location')->nullable();
            $table->string('company_website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
