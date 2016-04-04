<?php

namespace Che\AppBundle\ImageProcess;


class ImageProcess
{
    protected $imageDir = '';
    protected $thumbnailX = 200;
    protected $thumbnailY = 200;

    /**
     * Get Image Dir
     *
     * @return string
     */
    public function getImageDir()
    {
        return $this->imageDir;
    }

    /**
     * Set parameters
     *
     * @param array $params
     */
    public function setParams($params)
    {
        if (isset($params['thumbnailX'])) {
            $this->thumbnailX = $params['thumbnailX'];
        }
        if (isset($params['thumbnailY'])) {
            $this->thumbnailY = $params['thumbnailY'];
        }
        if (isset($params['imageDir'])) {
            $this->imageDir = $params['imageDir'];
        }
    }


    /**
     * @param string $imageDir
     */
    public function setImageDir($imageDir)
    {
        $this->imageDir = $imageDir;
    }

    /**
     * Thumbnail file
     *
     * @param $fileName
     *
     * @return string
     */
    public function getThumbnail($fileName)
    {
        $iMagick = new \Imagick($fileName);

        // Resize image
        // Unfortunately this approach stops gif animation. I need little bit more time to manage this
        $iMagick->resizeImage($this->thumbnailX, $this->thumbnailY, \Imagick::FILTER_GAUSSIAN, 1, true);

        $extension = strtolower($iMagick->getImageFormat());
        $fileName = $this->getImageDir() . uniqid() . '.' . $extension;
        $iMagick->writeImage($fileName);

        return $fileName;

    }



    /**
     * Process file
     *
     * @param $fileName
     *
     * @return string
     */
    public function getProcessedFile($fileName)
    {
        return $this->getThumbnail($fileName);
    }





}

