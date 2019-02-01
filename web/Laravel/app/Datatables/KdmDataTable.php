<?php

namespace App\DataTables;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;


class KdmDataTable extends DataTable
{

    protected $datatables;

    public function __construct(DataTables $dataTables)
    {
        $this->datatables = $dataTables;
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
//            ->addColumn('1ction', function($a) { return ['name'=>'action123']; })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax($this->getAjax())
//            ->addAction()
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get default ajax.
     *
     * @return array
     */
    protected function getAjax()
    {
        return [
            "data" => "function (d) {
                d._token = csrfToken;
            }",
            "type" => "post",
        ];
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'order'   => [[0, 'desc']],
            'dom' => 'lrtBpi',
//            'stateSave' => true,
            'stateSave' => false,
            'buttons' => [
//                'csv',
//                'excel',
                'postExcel',
                  //['extend' => 'print',  'text' => '<span><i class="fa fa-print"></i> 印刷</span>'],
                ['extend' => 'reload', 'text' => '<span><i class="fa fa-refresh"></i> 更新</span>'],
            ],
            'oLanguage' => [
                'oPaginate' => [
                    'sFirst'    => '最初',
                    'sPrevious' => '&laquo;',
                    'sNext'     => '&raquo;',
                    'sLast'     => '最後'
                ],
                'sEmptyTable'     => 'データが見つかりませんでした',
                'sInfo'           => '全_TOTAL_件中 _START_〜_END_件目を表示しています',
                'sInfoEmpty'      => '表示するデータがありません',
                'sInfoFiltered'   => '合計_MAX_件からフィルタしています',
                'sLengthMenu'     => '表示件数:  _MENU_',
                'sLoadingRecords' => '読み込み中・・・',
                'sProcessing'     => '読み込み中・・・',
                'sSearch'         => 'クイック検索:',
                'sZeroRecords'    => '対象のデータが見つかりませんでした',
            ],
        ];
    }

    public function render($view, $data = [], $mergeData = [])
    {
        $fileName = preg_replace("@^.+\\\\(.+)DataTable$@", "\\1", get_class($this));
        $view = strtolower(substr($fileName, 0, 1)) . substr($fileName, 1) . "." . $view;
        return parent::render($view, $data, $mergeData);
    }


    /**
     * Get printable columns.
     * 末尾が「操作」のため印刷時には取り除く
     *
     * @return array|string
     */
//    protected function printColumns()
//    {
//        $columns = parent::printColumns();
//        unset($columns[count($columns) - 1]);
//        return $columns;
//    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        $fileName = preg_replace("@^.+\\\\(.+)DataTable$@", "\\1", get_class($this));
        return strtolower(substr($fileName, 0, 1)) . substr($fileName, 1) . '_' . time();
    }


//    protected function buildExcelFile()
//    {
//        $fileName = $this->getFilename();
//        $columnNames = array_keys($this->getColumns());
//        $data = $this->getDataForExport();
//
//        return ExcelDownLoader::getAt($fileName, $columnNames, $data);
//    }
}
