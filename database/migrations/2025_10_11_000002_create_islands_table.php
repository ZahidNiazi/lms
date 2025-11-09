<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('islands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atoll_id');
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('atoll_id')->references('id')->on('atolls')->onDelete('cascade');
            $table->index(['created_by', 'atoll_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('islands');
    }
};
