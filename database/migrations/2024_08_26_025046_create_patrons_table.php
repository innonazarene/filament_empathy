<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patrons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_information_id')->constrained('company_information')->onDelete('cascade');
            $table->string('patron_name');
            $table->string('patron_email')->nullable();
            $table->string('patron_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrons');
    }
};
