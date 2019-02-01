<?php

namespace App\DataTables;

use App\Excel\ExcelDownLoader;
use App\Services\AggregationService;
use Illuminate\Support\Facades\DB;

class AggregationDataTable extends CurriculumDataTable
{
    const MODE_PREFECTURES = 'prefecture';
    const MODE_CITIES      = 'city';
    const MODE_SCHOOLS     = 'school';
    const MODE_CLASSES     = 'class';

    /**
     * @var String
     */
    protected $aggregationMode;

    /**
     * @return String
     */
    public function getAggregationMode()
    {
        return $this->aggregationMode;
    }

    /**
     * @param String $aggregationMode
     */
    public function setAggregationMode($aggregationMode)
    {
        $this->aggregationMode = $aggregationMode;
    }


    /**
     * @var Integer
     */
    protected $cityId;

    /**
     * @return Integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @param Integer $cityId
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
    }


    /**
     * @var Integer
     */
    protected $schoolId;

    /**
     * @return Integer
     */
    public function getSchoolId()
    {
        return $this->schoolId;
    }

    /**
     * @param Integer $schoolId
     */
    public function setSchoolId($schoolId)
    {
        $this->schoolId = $schoolId;
    }


    /**
     * @var Integer
     */
    protected $classId;

    /**
     * @return Integer
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * @param Integer $classId
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
//            'クラスID' => [
//                'name' => 'class_id',
//                'data' => 'class_id',
//                'className' => 'text-right',
//                "searchable" => false,
//                "visible" => false,
//                "orderable" => false
//            ],
        ];

        if($this->isBelowPrefectureMode()){
            $columns['市町村'] = [
                'name' => 'city_name',
                'data' => 'city_name',
                'className' => 'text-left text nowrap',
                "orderable" => false
            ];

            if($this->isBelowCityMode()){
                $columns['学校'] = [
                    'name' => 'school_name',
                    'data' => 'school_name',
                    'className' => 'text-left text nowrap',
                    "orderable" => false
                ];

                if($this->isBelowSchoolMode()){
                    $columns['クラス'] = [
                        'name' => 'class_name',
                        'data' => 'class_name',
                        'className' => 'text-left text nowrap',
                        "orderable" => false
                    ];
                }
            }
        }

        foreach($this->curriculum->units as $unit){
            $un = $unit->number;
//            $unitKey = '内容' . mb_convert_kana($un, "N", "utf-8");
//            $unitKey = '内容' . $un;
//            $columns[$unitKey . '計'] = [
//                'name' => 'passing_rate_unit_' . $un,
//                'data' => 'passing_rate_unit_' . $un,
//                'className' => 'text-right',
//                "searchable" => false,
//                "orderable" => false
//            ];
            foreach($unit->workbooks as $workbook){
                $wn = $workbook->number;
                $workbookKey = $wn === 0 ? "内容{$un}" : "補{$un}-{$wn}";
                $columns[$workbookKey] = [
                    'name' => "passing_rate_unit_{$un}_workbook_{$wn}",
                    'data' => "passing_rate_unit_{$un}_workbook_{$wn}",
                    'className' => 'text-right nowrap',
                    "searchable" => false,
                    "orderable" => false,
                    "cellExcelType" => ExcelDownLoader::CELL_TYPE_PERCENT
                ];
            }
        }
        $columns['平均'] = [
            'name' => 'passing_rate_class',
            'data' => 'passing_rate_class',
            'className' => 'text-right',
            "searchable" => false,
            "orderable" => false,
            "cellExcelType" => ExcelDownLoader::CELL_TYPE_PERCENT
        ];

        return $columns;
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        $params = array_merge(
            parent::getBuilderParameters(),
            [
                'scrollX' => true,
                'fixedColumns' =>[
                    'leftColumns'=> $this->getLeftColumns(),
                ]
            ]
        );


        return $params;
    }

    public function getLeftColumns(){
        return $this->isBelowSchoolMode() ? 3 : ($this->isBelowCityMode() ?  2 : 1);
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
        $tableName = "aggregation_curriculum_{$this->curriculum->id}_{$this->aggregationMode}_main";
        $query = DB::table($tableName)
            ->whereNull('deleted_at')
            ->select(DB::raw('*'));

        if($this->isBelowSchoolMode()){
            $query = $query->orderBy('class_name', 'asc')->orderBy('school_name', 'asc')->orderBy('city_name', 'asc');
        }elseif($this->isBelowCityMode()) {
            $query = $query->orderBy('school_order', 'asc')->orderBy('city_order', 'asc');
        }elseif($this->isBelowPrefectureMode()) {
            $query = $query->orderBy('city_order', 'asc');
        }

        return $query;
    }

    public function ajax()
    {
        $filtering = \Request::get('filtering');
        //$eloquent = Datatables::of($this->query());
        $eloquent = $this->datatables->queryBuilder($this->query());

        $rawColArr = array();
        foreach($this->curriculum->units as $unit){
            $un = $unit->number;
            foreach($unit->workbooks as $workbook){
                $wn = $workbook->number;
                $rawColArr[]="passing_rate_unit_{$un}_workbook_{$wn}";
            }
        }
        $rawColArr[] = 'passing_rate_class';
        //Set show html for culumn
        $eloquent->rawColumns($rawColArr);

        $eloquent
            ->filterColumn('city_name', function($query, $keyword) {
                $query->whereRaw("city_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('school_name', function($query, $keyword) {
                $query->whereRaw("school_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('class_name', function($query, $keyword) {
                $query->whereRaw("class_name like ?", ["%{$keyword}%"]);
            });

        //Filter by city, school, class
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

    private function isBelowPrefectureMode(){
        return $this->isCityMode() || $this->isSchoolMode() || $this->isClassMode();
    }

    private function isBelowCityMode(){
        return $this->isSchoolMode() || $this->isClassMode();
    }

    private function isBelowSchoolMode(){
        return $this->isClassMode();
    }

    private function isPrefectureMode(){
        return $this->aggregationMode === self::MODE_PREFECTURES;
    }

    private function isCityMode(){
        return $this->aggregationMode === self::MODE_CITIES;
    }

    private function isSchoolMode(){
        return $this->aggregationMode === self::MODE_SCHOOLS;
    }

    private function isClassMode(){
        return $this->aggregationMode === self::MODE_CLASSES;
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
            $filtering = $this->datatables->getRequest()->__get('filtering');
            $curri_id = $this->datatables->getRequest()->__get('curri_id');
            $prep_id = 1;
            $city_id = $filtering["city_id"];
            $school_id = $filtering["school_id"];

            $dataFooter = AggregationService::getFooterData($prep_id, $city_id, $school_id, $curri_id,true);
            return $dataFooter;

        }catch (\Exception $ex){
            return array();
        }
    }


    protected function getPartName(){
        return 'total';
    }
}
