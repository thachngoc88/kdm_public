<?php

use Illuminate\Database\Seeder;
use App\Services\Utils;
class ExistingPdfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $workbooks = \App\Workbook::all();

        foreach ($workbooks as $wb) {
            foreach (['q', 'a'] as $type) {
                $existPdf = \App\ExistingPdf::create([
                    "type" => $type,
                    "existing" => Utils::checkExistFileDownloadInWorkbook($type, $wb),
                    "workbook_id" => $wb->id,
                ]);
                $existPdf->save();
            }
        }
    }
}
