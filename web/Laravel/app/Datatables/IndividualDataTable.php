<?php

namespace App\DataTables;

use App\Excel\ExcelDownLoader;
use App\Services\IndividualService;
use App\Services\UpdaterTool;
use Illuminate\Support\Facades\DB;
use App\Curriculum;
use Yajra\Datatables\Datatables;


class IndividualDataTable extends CurriculumDataTable
{

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns['児童ID'] = [
            'name' => 'login_id',
            'data' => 'login_id',
            'className' => 'text-left nowrap',
            'orderable' => false,
        ];
        foreach($this->curriculum->units as $unit){
            $un = $unit->number;
//            $unitKey = '内容' . mb_convert_kana($un, "N", "utf-8");
            foreach($unit->workbooks as $workbook){
                $wn = $workbook->number;
                $workbookKey = $wn === 0 ? "内容{$un}" : "補{$un}-{$wn}";
                $columns[$workbookKey] = [
                    'name' => "u_{$un}_w_{$wn}",
                    'data' => "u_{$un}_w_{$wn}",
                    'className' => 'text-center  nowrap',
                    "orderable" => false,
                    "cellExcelType" => ExcelDownLoader::CELL_TYPE_MARK
                ];
            }
        }

        $columns[UpdaterTool::CIRCLE . '（チャレ）'] = [
            'name' => 'total_pass',
            'data' => 'total_pass',
            'className' => 'text-right nowrap',
            "orderable" => false,
        ];
        $columns[UpdaterTool::DELTA] = [
            'name' => 'count_f',
            'data' => 'count_f',
            'className' => 'text-right',
            "orderable" => false,
            "cellExcelType" => ExcelDownLoader::CELL_TYPE_NUMBER
        ];
        $columns[UpdaterTool::HYPHEN] = [
            'name' => 'count_nys',
            'data' => 'count_nys',
            'className' => 'text-right',
            "orderable" => false,
            "cellExcelType" => ExcelDownLoader::CELL_TYPE_NUMBER
        ];

        $columns['全体進捗率'] = [
            'name' => 'rate_imp',
            'data' => 'rate_imp',
            'className' => 'text-right',
            "orderable" => false,
            "cellExcelType" => ExcelDownLoader::CELL_TYPE_PERCENT
        ];
        return $columns;
    }
    public function html()
    {
        $builder = $this->builder();
        $columns = $this->getColumns();
        return $builder->columns($columns)
            ->ajax($this->getAjax())
//            ->addAction()
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return array_merge(
            parent::getBuilderParameters(),
            [
                'scrollX' => true,
                'fixedColumns' =>[
                    'leftColumns'=> 1,
                ]
            ]
        );
    }

    protected function getAjax()
    {
        return [
            "data" => "function (d) {
                d.filtering = {$this->getClassFilteringObjectScript()};
                d._token = csrfToken;
                d.curri_id= {$this->curriculum->id};
            }",
            "type" => "post",
        ];
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $tableName = "individual_curriculum_{$this->curriculum->id}_main";
        return DB::table($tableName)
            ->whereNull('deleted_at')
            ->select(DB::raw('*'))->orderBy('order_number');
    }

    public function ajax()
    {
        $eloquent = $this->datatables->queryBuilder($this->query());

        $rawColArr = array();
        foreach($this->curriculum->units as $unit){
            $un = $unit->number;
            foreach($unit->workbooks as $workbook){
                $wn = $workbook->number;
                $rawColArr[]="u_{$un}_w_{$wn}";
            }
        }
        $rawColArr[] = 'rate_imp';
        //Set show html for culumn
        $eloquent->rawColumns($rawColArr);

        //Edit value of column rate improve
        //$eloquent->editColumn('rate_imp', '{{$rate_imp}}%');

        $eloquent->filterColumn('city_name', function($query, $keyword) {
            $query->whereRaw("rate_imp = ?", ["%{$keyword}%"]);
        });

        //Filter by user id, city, school, class
        $filtering = \Request::get('filtering');
        foreach ($filtering as $key => $val) {
            //app('debugbar')->warning("Filtering key:".$key ." - value:".$val);
            switch ($key) {
                case 'city_id':
                    if(!empty($val) && $val != 'all'){
                        //app('debugbar')->warning('filter by city_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query
                                ->where("city_id", "=", $val);
                        }, true);
                    }
                    break;
                case 'school_id':
                    if(!empty($val) && $val != 'all'){
                        //app('debugbar')->warning('filter by school_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query
                                ->where("school_id", "=", $val);
                        }, true);
                    }
                    break;
                case 'class_id':
                    if(!empty($val) && $val != 'all'){
                        //app('debugbar')->warning('filter by class_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query
                                ->where("class_id", "=", $val);
                        }, true);
                    }
                    break;
            }
        }

        return $eloquent->make(true);
    }

    protected function buildExcelFile()
    {
        $fileName = $this->getFilename();
        $columns = $this->getColumns();
        $dataBody = $this->getDataForExport();
        $dataFooter = $this->getDataForExportFooter();
        return ExcelDownLoader::createExcelFile($fileName, $columns, $dataBody, $dataFooter);
    }

    protected function getDataForExport()
    {
        $data = parent::getDataForExport();
        return $data;
    }

    protected function getDataForExportFooter()
    {
        try{
            //$action = $this->datatables->getRequest()->__get('action');
            $filtering = $this->datatables->getRequest()->__get('filtering');
            $curri_id = $this->datatables->getRequest()->__get('curri_id');

            $prep_id = 1;
            $city_id = $filtering["city_id"];
            $school_id = $filtering["school_id"];
            $class_id = $filtering["class_id"];

            $dataFooter = IndividualService::getFooterData($prep_id, $city_id, $school_id, $class_id, $curri_id,true);
            return $dataFooter;

        }catch (\Exception $ex){
            return array();
        }
    }

    protected function getPartName(){
        return 'children';
    }
}
