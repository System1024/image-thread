<?php

namespace Che\AppBundle\Export;


class ExportFactory
{
    /**
     * Create needed export class instance
     *
     * @param $format
     * @param $fileName
     *
     * @return ExportInterface
     * @throws ExportClassNotFoundException
     */
    public static function getByFormat($format, $fileName)
    {
        $class = 'Che\AppBundle\Export\\'.ucfirst($format);

        if (!class_exists($class)) {
            throw new ExportClassNotFoundException('Export class not found');
        }
        return new $class($fileName);
    }
}