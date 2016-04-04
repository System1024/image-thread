<?php

namespace Che\AppBundle\Export;

use Che\AppBundle\Entity\Images;

class Csv extends ExportAbstract
{

    public function export($data)
    {
        $handle = fopen($this->fileName, 'w+');

        /** @var Images $value */
        foreach($data as $value) {
            fputcsv($handle, array($value->getComment(), $value->getImage()), ';');
        }
        fclose($handle);

    }

    public function getContentType()
    {
        return 'text/csv';
    }

    public function getExtension()
    {
        return 'csv';
    }
}