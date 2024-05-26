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
        Schema::create('workflow_dashboards', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('workflow_instance_id')->constrained();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign("user_id")->references('id')->on("users")->cascadeOnDelete();

            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign("role_id")->references('id')->on("roles")->cascadeOnDelete();

            $table->unsignedBigInteger('workflow_step_id')->comment('Current step');
            $table->foreign("workflow_step_id")->references('id')->on("workflow_steps")->cascadeOnDelete();

            $table->integer('read_status')->default(0);
            $table->datetime("view_date")->nullable();
            $table->text("description")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_dashboards');
    }
};
