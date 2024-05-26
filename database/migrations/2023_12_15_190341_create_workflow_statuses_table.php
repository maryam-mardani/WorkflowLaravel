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
        Schema::create('workflow_statuses', function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->tinyInteger("description_is_required")->comment('The description field is required when this status is selected')->default(0);

            $table->unsignedBigInteger('workflow_id');
            $table->foreign("workflow_id")->references('id')->on("workflows")->cascadeOnDelete();

            $table->unsignedBigInteger('workflow_function_id')->comment('The behavior of status');
            $table->foreign("workflow_function_id")->references('id')->on("workflow_functions")->cascadeOnDelete();

            $table->unsignedBigInteger('selectable_in_workflow_step_id')->comment('This status is selectable in which steps');
            $table->foreign("selectable_in_workflow_step_id")->references('id')->on("workflow_steps")->cascadeOnDelete();

            $table->unsignedBigInteger('next_workflow_activity_id')->comment('If behavior is nextStep, select next step')->nullable();
            $table->foreign("next_workflow_activity_id")->references('id')->on("workflow_steps")->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_statuses');
    }
};
