<?php

namespace App\DataTables;

use App\Excel\ExcelDownLoader;
use App\User;

class UsersDataTable extends ClassFilteringDataTable
{

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'ID' => [
                'name' => 'id',
                'data' => 'id',
                'className' => 'text-left',
                "searchable" => false,
                "visible" => false
            ],
            '有効' => [
                'name' => 'enabled',
                'data' => 'enabled',
                'className' => 'text-center',
            ],
            'ユーザ名' => [
                'name' => 'login_id',
                'data' => 'login_id',
                'className' => 'text-left',
            ],
            '市町村' => [
                'name' => 'city_name',
                'data' => 'city_name',
                'className' => 'text-left'
            ],
            '学校' => [
                'name' => 'school_name',
                'data' => 'school_name',
                'className' => 'text-left'
            ],
            'クラス' => [
                'name' => 'class_name',
                'data' => 'class_name',
                'className' => 'text-left'
            ],

        ];
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
                'order'   => [[0, 'asc']],
            ]
        );
    }

    protected function getAjax()
    {
        return [
            "data" => "function (d) {
                d.filtering = {$this->getClassFilteringObjectScript()};
                d.filtering.login_id = $('input.filter').val();
                d._token = csrfToken;
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

        //app('debugbar')->info("call query() method...............");
        $query = User
            ::leftJoin('prefecture_users AS PU', 'users.id', '=', 'PU.user_id')
            ->leftJoin('city_users AS CU',       'users.id', '=', 'CU.user_id')
            ->leftJoin('school_users AS SU',     'users.id', '=', 'SU.user_id')
            ->leftJoin('challenge_users AS CHU', 'users.id', '=', 'CHU.user_id')
            ->leftJoin('prefectures AS P', 'PU.prefecture_id', '=', 'P.id')
            ->leftJoin('cities AS C',      'CU.city_id',       '=', 'C.id')
            ->leftJoin('schools AS S',     'SU.school_id',     '=', 'S.id')

            ->leftJoin('cities AS SC', 'S.city_id', '=', 'SC.id')

            ->leftJoin('classes AS CL',    'CHU.class_id',     '=', 'CL.id')
            ->leftJoin('grades AS G',   'CL.grade_id',  '=', 'G.id')
            ->leftJoin('schools AS CS', 'CL.school_id', '=', 'CS.id')
            ->leftJoin('cities AS CC', 'CS.city_id', '=', 'CC.id')

            ->where(function($query){
                $query  ->where(  'users.login_id', 'like', 'a%')
                    ->orWhere('users.login_id', 'like', 'b%')
                    ->orWhere('users.login_id', 'like', 'c%')
                    ->orWhere('users.login_id', 'like', 'd%')
                    ->orWhere('users.login_id', 'like', 'e%')
                    ->orWhere('users.login_id', 'like', 'f%')
                    ->orWhere('users.login_id', 'like', 'g%')
                    ->orWhere('users.login_id', 'like', 'h%')
                    ->orWhere('users.login_id', 'like', 'i%')
                    ->orWhere('users.login_id', 'like', 'j%')
                    ->orWhere('users.login_id', 'like', 'k%')
                    ->orWhere('users.login_id', 'like', 'l%')
                    ->orWhere('users.login_id', 'like', 'm%')
                    ->orWhere('users.login_id', 'like', 'n%')
                    ->orWhere('users.login_id', 'like', 'o%')
                    ->orWhere('users.login_id', 'like', 'p%')
                    ->orWhere('users.login_id', 'like', 'q%')
                    ->orWhere('users.login_id', 'like', 'r%')
                    ->orWhere('users.login_id', 'like', 's%')
                    ->orWhere('users.login_id', 'like', 't%')
                    ->orWhere('users.login_id', 'like', 'u%')
                    ->orWhere('users.login_id', 'like', 'v%')
                    ->orWhere('users.login_id', 'like', 'w%')
                    ->orWhere('users.login_id', 'like', 'x%')
                    ->orWhere('users.login_id', 'like', 'y%')
                    ->orWhere('users.login_id', 'like', 'z%')
                    ->orWhere('users.login_id', 'REGEXP', '[0-9][0-9][0-9][0-9][0-9]');
            })


            ->selectRaw(
                'users.id, ' .
                "{$this->createEnabled()}, " .
                "{$this->createCityName()}, " .
                "{$this->createSchoolName()}, " .
                "{$this->createClassName()}, " .
                "{$this->createLoginName()} "
            );

        //app('debugbar')->info($query);
        return $this->applyScopes($query);
    }
    public function ajax()
    {
        $filtering = \Request::get('filtering');
        $eloquent = $this->datatables->eloquent($this->query());
        $eloquent
            ->filterColumn('city_name', function($query, $keyword) {
                $query->whereRaw("CC.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('school_name', function($query, $keyword) {
                $query->whereRaw("CS.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('class_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(`G`.`number`, '-', `CL`.`name`) like ?", ["%{$keyword}%"]);
            });


        $filtering = \Request::get('filtering');
        //Filter by user id, city, school, class
        foreach ($filtering as $key => $val) {
            //app('debugbar')->warning("Filtering key:".$key ." - value:".$val);
            switch ($key) {
                case 'login_id':
                    if(!empty($val)){
                        //app('debugbar')->warning('filter by user_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query
                                ->where("login_id", "like", "%$val%");
                        }, true);
                    }
                    break;
                case 'city_id':
                    if(!empty($val) && $val != 'all'){
                        //app('debugbar')->warning('filter by city_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query->where(function ($query) use($val) {
                                $query
                                    ->where("CU.city_id", "=", $val)
                                    ->orWhere("CS.city_id", "=", $val);
                            });
                        }, true);
                    }
                    break;
                case 'school_id':
                    if(!empty($val) && $val != 'all'){
                        //app('debugbar')->warning('filter by school_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query
                                ->where("SU.school_id", "=", $val)
                                ->orWhere("CL.school_id", "=", $val);
                        }, true);
                    }
                    break;
                case 'class_id':
                    if(!empty($val) && $val != 'all'){
                        //app('debugbar')->warning('filter by class_id = '.$val);
                        $eloquent = $eloquent->filter(function($query) use($val){
                            $query->where("class_id", "=", $val);
                        }, true);
                    }
                    break;
            }
        }
        return $eloquent->make(true);
    }
    private function createEnabled(){
        return
            "IF(`users`.`enabled`, '○', '" . $this->getMinusTag() . "') AS enabled";
    }
    private function createCityName(){
        return
            "IFNULL(`CC`.`name`, IFNULL(`SC`.`name`, IFNULL(`C`.`name`, '" . $this->getMinusTag() . "'))) AS city_name";
    }
    private function createSchoolName(){
        return
            "IFNULL(`CS`.`name`, IFNULL(`S`.`name`, '" . $this->getMinusTag() . "')) AS school_name";
    }
    private function createClassName(){
        return
            "IF(`CL`.`name`, IF(`G`.`number`, CONCAT(`G`.`number`, '-', `CL`.`name`), '" . $this->getMinusTag() . "'), '" . $this->getMinusTag() . "') AS class_name";
    }
    private function createLoginName(){
        return "users.login_id";
    }
    private function getMinusTag(){
//        return '<i class="fa fa-minus" aria-hidden="true"></i>';
        return '-';
    }

    protected function buildExcelFile()
    {
        $fileName = $this->getFilename();
        $columns = $this->getColumns();
        $columnsUpdate = $this->removeColumnId($columns);
        $data = $this->getDataForExport();
        $dataUpdate = $this->removeDataId($data);
        return ExcelDownLoader::createExcelFile($fileName, $columnsUpdate, $dataUpdate);
    }

    private function removeDataId($data)
    {
        if(!empty($data)){
            return array_map(function($row){
                return array_slice($row,1);
            }, $data);
        }
        return $data;
    }

    private function removeColumnId($columns)
    {
        return array_slice($columns,1);
    }

}
