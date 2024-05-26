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
        Schema::create('workflow_functions', function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->string("function");
            $table->text("description");
            $table->integer("need_next")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('workflow_functions');
        Schema::enableForeignKeyConstraints();
    }
};
