<?php

namespace Che\AppBundle\Export;


class Zip  extends ExportAbstract
{

    public function export($data)
    {
        // ZipArchive can't work with php://output
        $tmpZipName = uniqid() . '.zip';
        $tmpCSVName = uniqid() . '.csv';

        $zip = new \ZipArchive();
        $zip->open($tmpZipName, \ZipArchive::CREATE);

        $options = array('add_path' => 'images/', 'remove_all_path' => TRUE);
        $zip->addGlob(__DIR__.'/../../../web/img/*.*', GLOB_BRACE, $options);

        $csv = new Csv($tmpCSVName);
        $csv->export($data);

        $zip->addFile($tmpCSVName, 'export.' . $csv->getExtension());

        $zip->close();

        readfile($tmpZipName);
        unlink($tmpZipName);
        unlink($tmpCSVName);

    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'application/zip';
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return 'zip';
    }
}