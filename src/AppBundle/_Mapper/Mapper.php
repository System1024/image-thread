<?php

namespace AppBundle\Mapper;


use Che\AppBundle\Entity\Images;
use Che\AppBundle\ImageProcess\ImageProcess;

class Mapper
{
    public static function map(ImageProcess $image)
    {
        $entity = new Images();
        $entity->setImage($image->getProcessedFile());
        $entity->setThumbnail($image->getThumbnail());

    }

}