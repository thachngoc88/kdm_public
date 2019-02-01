<?php

namespace App\DataTables;

use App\Curriculum;

class CurriculumDataTable extends ClassFilteringDataTable
{
    /**
     * @var Curriculum
     */
    protected $curriculum;

    /**
     * @return Curriculum
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * @param Curriculum $curriculum
     */
    public function setCurriculum($curriculum)
    {
        $this->curriculum = $curriculum;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        $curriculumFileName = $this->getCurriculumFileName();
        $partName = $this->getPartName();
        $timeName = $this->getTimeName();
        return "{$curriculumFileName}_{$partName}_{$timeName}";
    }

    protected function getCurriculumFileName(){
        $result = null;
        switch($this->curriculum->subject->name){
            case '国語':
                $result = 'kokugo';
                break;
            case '算数':
                $result = 'sansu';
                break;

        }
        return $result;
    }

    protected function getPartName(){
        return preg_replace("@^.+\\\\(.+)DataTable$@", "\\1", get_class($this));
    }

    protected function getTimeName(){
        return date("YmdHis");
    }
}
