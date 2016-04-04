<?php

namespace Che\AppBundle\Export;


interface ExportInterface
{
    /**
     * Export data in defined format
     *
     * @param array $data
     *
     * @return mixed
     */
    public function export($data);

    /**
     * Return http content type
     *
     * @return string
     */
    public function getContentType();

    /**
     * Return default file extension
     *
     * @return string
     */
    public function getExtension();
}