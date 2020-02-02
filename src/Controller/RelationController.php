<?php

namespace App\Controller;

use App\Model\Request\CommentRequestModel;
use App\Model\Response\CommentResponse;
use App\Service\CommentService;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RelationController extends BaseController
{
    private $commentService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        CommentService $commentService,
        ValidatorInterface $validator
    ) {
        $this->commentService = $commentService;
        $this->validator = $validator;
    }

    /**
     * @Route("/relation/football-match/{id}", name="comment_add",  methods={"POST"})
     */
    public function addAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $commentRequestModel = new CommentRequestModel($request->request->get('content'));

        $validationErrors = $this->validator->validate($commentRequestModel);
        $this->handleErrors($validationErrors);

        $comment = $this->commentService->createAndSaveComment(
            $id,
            $commentRequestModel->getContent()
        );

        $response = new CommentResponse(
            $comment->getId(),
            $commentRequestModel->getContent(),
            $comment->getDate()
        );

        return $this->view($response);
    }

    /**
     * @Route("/relation/{id}", name="comment_edit",  methods={"PATCH"})
     */
    public function editAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $commentRequestModel = new CommentRequestModel($request->request->get('content'));

        $validationErrors = $this->validator->validate($commentRequestModel);
        $this->handleErrors($validationErrors);

        $comment = $this->commentService->update(
            $id,
            $commentRequestModel->getContent()
        );

        $response = new CommentResponse(
            $comment->getId(),
            $commentRequestModel->getContent(),
            $comment->getDate()
        );

        return $this->view($response);
    }

    /**
     * @Route("/relation/{id}", name="comment_delete",  methods={"DELETE"})
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->commentService->delete($id);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/relation/football-match/{id}/complete", name="comments_view",  methods={"GET"})
     */
    public function viewAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $comments = $this->commentService->findComments($id);

        return $this->view($comments);
    }
}
