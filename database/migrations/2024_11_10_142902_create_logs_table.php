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
		Schema::create('logs', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
			$table->string('action');
			$table->string('model')->nullable();
			$table->unsignedBigInteger('model_id')->nullable();
			$table->json('data')->nullable();
			$table->ipAddress('ip_address')->nullable();
			$table->timestamp('created_at')->useCurrent();
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
