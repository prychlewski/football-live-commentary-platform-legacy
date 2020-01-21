<?php

namespace App\Controller;

use App\Model\Request\EventCommentRequestModel;
use App\Model\Response\EventCommentResponse;
use App\Service\EventCommentService;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RelationController extends BaseController
{
    private $eventCommentService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        EventCommentService $eventCommentService,
        ValidatorInterface $validator
    ) {
        $this->eventCommentService = $eventCommentService;
        $this->validator = $validator;
    }

    /**
     * @Route("/relation/event/{id}", name="event_comment_add",  methods={"POST"})
     */
    public function addAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventCommentRequestModel = new EventCommentRequestModel($request->request->get('content'));

        $validationErrors = $this->validator->validate($eventCommentRequestModel);
        $this->handleErrors($validationErrors);

        $eventComment = $this->eventCommentService->createAndSaveEventComment(
            $id,
            $eventCommentRequestModel->getContent()
        );

        $response = new EventCommentResponse(
            $eventComment->getId(),
            $eventCommentRequestModel->getContent(),
            $eventComment->getDate()
        );

        return $this->view($response);
    }

    /**
     * @Route("/relation/{id}", name="event_comment_edit",  methods={"PATCH"})
     */
    public function editAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventCommentRequestModel = new EventCommentRequestModel($request->request->get('content'));

        $validationErrors = $this->validator->validate($eventCommentRequestModel);
        $this->handleErrors($validationErrors);

        $eventComment = $this->eventCommentService->update(
            $id,
            $eventCommentRequestModel->getContent()
        );

        $response = new EventCommentResponse(
            $eventComment->getId(),
            $eventCommentRequestModel->getContent(),
            $eventComment->getDate()
        );

        return $this->view($response);
    }

    /**
     * @Route("/relation/{id}", name="event_comment_delete",  methods={"DELETE"})
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->eventCommentService->delete($id);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/relation/event/{id}/complete", name="event_comments_view",  methods={"GET"})
     */
    public function viewAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $eventComments = $this->eventCommentService->findEventComments($id);

        return $this->view($eventComments);
    }
}
