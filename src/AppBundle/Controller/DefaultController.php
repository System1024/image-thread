<?php

namespace Che\AppBundle\Controller;

use Che\AppBundle\Export\ExportFactory;
use Che\AppBundle\Service\ImageThread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $imageThread = $this->get('app.imagethread');
        $messages = $imageThread->getMessages();
        $messagesCount = count($messages);
        $viewCount = $imageThread->getAndUpdateViewCount();

        return $this->render('default/index.html.twig', [
            'messages' => $messages,
            'messagesCount' => $messagesCount,
            'viewCount' => $viewCount
        ]);
    }

    /**
     * @Route("/export/{format}", name="export")
     */
    public function exportAction($format)
    {

        $response = new StreamedResponse();

        $messages = $this->get('app.imagethread')->getMessages();

        $exporter = ExportFactory::getByFormat($format, 'php://output');
        $response->setCallback(function() use ($exporter, $messages) {
            $exporter->export($messages);
        });


        $response->setStatusCode(200);
        $response->headers->set('Content-Type', $exporter->getContentType());
        $response->headers->set('Content-Disposition','attachment; filename="export.'.$exporter->getExtension().'"');
        return $response;
    }

    /**
     * @return Response
     *
     * @Route("/getmessages")
     */
    public function getMessagesAction()
    {
        $imageThread = $this->get('app.imagethread');
        $messages = $imageThread->getMessages();

        $resp = array();
        $resp['messagesCount'] = count($messages);
        $resp['viewCount'] = $imageThread->getViewCounter();
        $resp['content'] = $this->get('twig')->render('default/messages.html.twig', [
            'messages' => $messages
        ]);

        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($resp));

        return $response;
    }

    /**
     * @param Request $request
     *
     * @Route("/newpost", name="newpost")
     * @return Response
     */
    public function newPostAction(Request $request)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */

        $message = array(
            'code' => Response::HTTP_OK
        );

        foreach($request->files as $file) {
            if ($file) {
                $fileName = $file->getPathname();
                if (!empty($fileName)) {
                    try {
                        $service = $this->get('app.imagethread');
                        $service->saveMessage($fileName, $request->request->get('imageTitle'));
                    } catch (\ImagickException $e) {
                        $message['code'] = Response::HTTP_BAD_REQUEST;
                        $message['message'] = 'Format not supported';
                    } catch (\Exception $e) {
                        $message['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                        $message['message'] = 'Internal server error';
                    }
                }
            }
        }

        $resp = new Response();
        $resp->setStatusCode($message['code']);
        if (isset($message['message'])) {
            $resp->setContent($message['message']);
        }
        return $resp;
    }

    /**
     * @return Response
     *
     * @Route("/getcounts")
     */
    public function getCountsAction()
    {
        $imageThread = $this->get('app.imagethread');
        $messagesCount = $imageThread->getMessagesCount();
        $viewCount = $imageThread->getViewCounter();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'messagesCount' => $messagesCount,
            'viewCount' => $viewCount
        ]));

        return $response;
    }
}
