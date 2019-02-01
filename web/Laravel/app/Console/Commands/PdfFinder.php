<?php

namespace App\Console\Commands;

use App\Services\Utils;
use Illuminate\Console\Command;

class PdfFinder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $workbooks = \App\Workbook::all();

        foreach ($workbooks as $wb) {
            for($i = 0; $i < 2; $i++){
                $type = !$i ? 'q':'a';
                \App\ExistingPdf::updateOrCreate(
                    ['type' => $type,'workbook_id' => $wb->id],
                    ['existing' => Utils::checkExistFileDownloadInWorkbook($type,$wb)]
                );
            }
        }
    }
}
