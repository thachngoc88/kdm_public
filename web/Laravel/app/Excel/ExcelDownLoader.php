<?php

namespace App\Excel;

use App\Product;
use App\Trader;
use Exception;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class ExcelDownLoader
{
    const ACTION_COLUMN_KEY = '操作';
    const CELL_TYPE_TEXT = '1';
    const CELL_TYPE_MARK = '2';
    const CELL_TYPE_NUMBER = '3';
    const CELL_TYPE_PERCENT = '4';


    private static function writeHeaders($sheet, $columnNames){
        $col = 0;
        foreach($columnNames as $name) {
            $data = self::getColumn($name);
            $sheet->setCellValueByColumnAndRow($col++, 1, $data);
        }
    }

    private static function getColumn($name){
        $columns = [
            'MARU2' => '◎ ◯',
            'MARU3' => '(◎)',
            'MARU1' => '△',
            'MARU0' => '-',
        ];
        return array_key_exists($name, $columns) ? $columns[$name] : $name;
    }

    private static function writeBody($sheet, $row_idx = 2, $dataBody, $columns){
        $columnNames = array_keys($columns);
        foreach ($dataBody as $rowData){
            $col_idx = 0;
            foreach ($rowData as $value){
                $cellType = self::getCellType($columns, $columnNames, $col_idx);
                self::setCellValue($sheet, $row_idx, $col_idx, $value, $cellType);
                $col_idx++;
            }
            $row_idx++;
        }
    }

    private static function writeFooter($sheet, $row_idx, $dataFooter){
        foreach ($dataFooter as $rowData){
            $col_idx = 0;
            foreach ($rowData as $value){
                if($col_idx == 0 || trim($value) == "" || trim($value) == "-"){
                    self::setCellValueString($sheet, $row_idx, $col_idx, $value);
                } else {
                    self::setCellValuePercent($sheet, $row_idx, $col_idx, $value);
                }
                $col_idx++;
            }
            $row_idx++;
        }
    }

    public static function createExcelFile($fileName, $columns, $dataBody, $dataFooter = null)
    {
        $excel = app('excel');
        return $excel->create($fileName, function (LaravelExcelWriter $excel) use ($columns, $dataBody, $dataFooter) {
            $excel->sheet('エクスポートデータ', function (LaravelExcelWorksheet $sheet) use ($columns, $dataBody, $dataFooter) {
                $columnNames = array_keys($columns);
                self::writeHeaders($sheet, $columnNames);
                self::writeBody($sheet,2 , $dataBody, $columns, $columnNames);
                if(!empty($dataFooter)){
                    $row_idx_footer = 2 + count($dataBody);
                    self::writeFooter($sheet, $row_idx_footer, $dataFooter);
                }
            });
        });
    }

    private static function setCellValue($sheet, $row_idx, $col_idx, $value, $cellType){
        switch ($cellType){
            case Self::CELL_TYPE_TEXT:
                self::setCellValueString($sheet, $row_idx, $col_idx, $value);
                break;
            case Self::CELL_TYPE_MARK:
                Self::setCellValueMark($sheet, $row_idx, $col_idx, $value);
                break;
            case Self::CELL_TYPE_NUMBER:
                self::setCellValueNumber($sheet, $row_idx, $col_idx, $value);
                break;
            case Self::CELL_TYPE_PERCENT:
                self::setCellValuePercent($sheet, $row_idx, $col_idx, $value);
                break;
            default:

        }
    }

    private static function setCellValueString($sheet, $row_idx, $col_idx, $value){
        $sheet->setCellValueExplicitByColumnAndRow($col_idx,$row_idx, $value, \PHPExcel_Cell_DataType::TYPE_STRING2);
    }

    private static function setCellValueNumber($sheet, $row_idx, $col_idx, $value){
        $sheet->setCellValueExplicitByColumnAndRow($col_idx,$row_idx, $value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
    }

    private static function setCellValueMark($sheet, $row_idx, $col_idx, $value){
        $style = self::getStyleHorizontalAlignment(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getCellByColumnAndRow($col_idx, $row_idx)->getStyle()->applyFromArray($style);
        $sheet->setCellValueExplicitByColumnAndRow($col_idx,$row_idx, $value, \PHPExcel_Cell_DataType::TYPE_STRING2);
    }

    private static function setCellValuePercent($sheet, $row_idx, $col_idx, $value)
    {
        try{
            $valueConvert = (float)substr($value, 0, -1);//remove % at end
            $style = Self::getStyleNumberFormat(\PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $sheet->getCellByColumnAndRow($col_idx, $row_idx)->getStyle()->getNumberFormat()->applyFromArray($style);
            $sheet->setCellValueByColumnAndRow($col_idx, $row_idx, sprintf('%.4f', $valueConvert / 100));
        } catch (Exception $ex) {
            $sheet->setCellValueByColumnAndRow($col_idx, $row_idx, "");
        }
    }

    private static function getStyleHorizontalAlignment($alignment){
        return array(
            'alignment' => array(
                'horizontal' => $alignment
            )
        );
    }

    private static function getStyleNumberFormat($numberFormat){
        return array(
            'code' => $numberFormat
        );
    }

    private static function getCellType($columns, $columnNames, $col_idx)
    {
        $cellType = Self::CELL_TYPE_TEXT;
        foreach ($columnNames as $index => $value) {
            $column = $columns[$value];
            if (array_key_exists("cellExcelType", $column) && $index == $col_idx) {
                $cellType = $column["cellExcelType"];

            }
        }
        return $cellType;
    }
}
