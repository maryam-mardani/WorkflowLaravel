<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkflowFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('workflow_functions')->truncate();
        $insert = [
            ['id' => 1,'title' => 'Edit & no action','function' => 'noAction','need_next' => 0,'description' => 'In this status, Workflow will stay in current step'],
            ['id' => 2,'title' => 'Return to the previous step','function' => 'prevStep','need_next' => 0,'description' =>'In this status, workflow will return to the previous step'],
            ['id' => 3,'title' => 'Send to the next step','function' => 'nextStep','need_next' => 1,'description' =>'In this status, workflow will send to the next step'],
            ['id' => 4,'title' => 'Archive','function' => 'archive','need_next' => 0,'description' =>'In this status, workflow will remove from all dashboard'],
        ];
        DB::table('workflow_functions')->insert($insert);

        Schema::enableForeignKeyConstraints();
    }

}
