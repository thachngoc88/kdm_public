<?php

namespace App\DataTables;


class ClassFilteringDataTable extends KdmDataTable
{
    protected function getClassFilteringObjectScript(){
        return "{
                city_id: $('#city-select option:selected').val(),
                school_id: $('#school-select option:selected').val(),
                class_id: $('#class-select option:selected').val()
            }";
    }
}
