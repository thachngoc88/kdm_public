<?php

use Illuminate\Database\Seeder;
use App\Services\Utils;
class DummyExistingPdfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::statement('SET CONSTRAINTS ALL DEFERRED;');
        \App\ExistingPdf::truncate();
        DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');

        $workbooks = \App\Workbook::all();

        foreach ($workbooks as $wb) {
            for($i = 0; $i < 2; $i++){
                $existpdf = \App\ExistingPdf::create([
                    "type" => !$i ? 'q':'a',
                    "existing" => Utils::checkExistFileDownloadInWorkbook(!$i ? 'q':'a',$wb),
                    "workbook_id" => $wb->id,
                ]);
                $existpdf->save();
            }
        }
    }
}
