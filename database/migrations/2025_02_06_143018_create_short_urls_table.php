<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->string('short_code', 10)->collation('utf8mb4_bin')->unique();
            $table->string('original_url', 2048);
            $table->string('delete_code')->collation('utf8mb4_bin')->unique();
            $table->dateTime('expired_at')->nullable();
            $table->unsignedBigInteger('clicks')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
