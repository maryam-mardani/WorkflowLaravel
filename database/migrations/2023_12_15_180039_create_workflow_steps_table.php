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
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->string("systemic_title")->nullable();
            $table->text("description")->nullable();

            $table->unsignedBigInteger('workflow_id');
            $table->foreign("workflow_id")->references('id')->on("workflows")->cascadeOnDelete();

            $table->unsignedBigInteger('role_id')->comment('witch role has access to this step');
            $table->foreign("role_id")->references('id')->on("roles")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
