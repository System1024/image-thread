<?php

namespace Che\AppBundle\Service;

use Che\AppBundle\ImageProcess\ImageProcess;
use Che\AppBundle\Entity\Images;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ImageThread
{
    protected $imageProcessor;
    protected $doctrine;
    protected $viewRepository;
    protected $imageRepository;

    /**
     * ImageThread constructor.
     *
     * @param ImageProcess $imageProcessor
     * @param Registry $doctrine
     */
    public function __construct(ImageProcess $imageProcessor, Registry $doctrine)
    {
        $this->imageProcessor = $imageProcessor;
        $this->doctrine = $doctrine;
        $this->viewRepository = $doctrine->getRepository('AppBundle:Views');
        $this->imageRepository = $doctrine->getRepository('AppBundle:Images');

    }

    /**
     * Save message to DB
     *
     * @param $fileName
     * @param $comment
     */
    public function saveMessage($fileName, $comment)
    {
        $imageFilePath = $this->imageProcessor->getProcessedFile($fileName);
        $pathInfo = pathinfo($imageFilePath);
        $imageFileName = $pathInfo['filename'].'.'.$pathInfo['extension'];

        $entity = new Images();

        $entity->setImage($imageFileName);
        $entity->setAdded(new \DateTime());
        $entity->setComment($comment);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();


    }

    /**
     * Get ordered messages list
     *
     * @return \Che\AppBundle\Entity\Images[]
     */
    public function getMessages()
    {
        return $this->imageRepository->getMessages();
    }

    /**
     * Get count of messages
     *
     * @return int
     */
    public function getViewCounter()
    {
        return  $this->viewRepository->getCount();
    }

    /**
     * Increase view counter
     */
    public function updateViewCount()
    {
        $this->viewRepository->updateCount();
    }

    /**
     * Get and increase view counter
     *
     * @return int
     */
    public function getAndUpdateViewCount()
    {
        $count = $this->getViewCounter();
        $this->updateViewCount();
        return $count;
    }

    /**
     * Get message counter
     *
     * @return int
     */
    public function getMessagesCount()
    {
        return $this->imageRepository->getMessagesCount();
    }



}