<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class ChecklistItemSeeder extends SpreadsheetSeeder
{

    public function run()
    {
        $this->file = '/database/seeders/csv/checklist_items.xlsx';
        $this->tablename = 'checklist_items';
        $this->truncate = false;
        $this->timestamps = false;

        DB::connection()->unprepared('SET IDENTITY_INSERT [dbo].[checklist_items] ON;');
        parent::run();
        DB::connection()->unprepared('SET IDENTITY_INSERT [dbo].[checklist_items] OFF;');
    }
}
