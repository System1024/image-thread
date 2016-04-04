<?php

namespace Che\AppBundle\Export;


use Che\AppBundle\Entity\Images;

class Excel extends ExportAbstract
{

    public function export($data)
    {
        $phpExcel = new \PHPExcel();
        $phpExcel->setActiveSheetIndex(0);

        $activeSheet = $phpExcel->getActiveSheet();

        $i = 0;
        /** @var Images $value */
        foreach($data as $value) {
            $activeSheet->setCellValueByColumnAndRow(0,$i,$value->getComment());
            $activeSheet->setCellValueByColumnAndRow(1,$i,$value->getImage());
            $i++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
        $writer->save($this->fileName);
    }

    public function getContentType()
    {
        return 'text/vnd.ms-excel; charset=utf-8';
    }

    public function getExtension()
    {
        return 'xlsx';
    }
}