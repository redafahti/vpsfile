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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->text('username');
            $table->text('firstname');
            $table->text('lastname');
            $table->text('email');
            $table->string('password');
            $table->text('mobile');
            $table->text('profile_picture');
            $table->text('qrcode');
            $table->text('firebase_id');
            $table->text('expo_push_token');
            $table->boolean('email_verified')->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
