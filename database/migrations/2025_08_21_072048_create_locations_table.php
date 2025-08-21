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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_type_id');
            $table->foreignId('parent_id')->nullable()->constrained('locations', 'id')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('name_kh', 100)->unique();
            $table->string('name_en', 80)->unique()->nullable();
            $table->string('code', 12)->unique();
            $table->string('postal_code', 14)->unique()->nullable();
            $table->string('coordination')->nullable();            
            $table->text('reference')->nullable();
            $table->text('note')->nullable();
            $table->string('created_by', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
