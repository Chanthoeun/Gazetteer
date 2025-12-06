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
        Schema::disableForeignKeyConstraints();

        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('parent_id')->constrained('places', 'id')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code', 10)->unique();
            $table->string('khmer', 150)->unique();
            $table->string('latin', 150)->unique()->nullable();
            $table->string('postal_code', 10)->unique()->nullable();
            $table->geography('geo_location')->nullable();
            $table->geography('geo_boundary')->nullable();
            $table->string('reference')->nullable();
            $table->date('issued_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
