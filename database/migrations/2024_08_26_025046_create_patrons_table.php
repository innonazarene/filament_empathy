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
            $table->string('patron_images');
            $table->text('patron_description');
            $table->text('twitter_link');
            $table->text('fb_link');
            $table->text('penterest_link');
            $table->text('youtube_link');
            $table->text('linkedin_link');
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
