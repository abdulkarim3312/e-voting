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
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('full_name_eng')->nullable();
            $table->string('pin')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->string('designation_bng')->nullable();
            $table->string('designation_status')->nullable();
            $table->string('office')->nullable();
            $table->string('office_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
