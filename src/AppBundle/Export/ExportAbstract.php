<?php

namespace Che\AppBundle\Export;


abstract class ExportAbstract implements ExportInterface
{
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

}