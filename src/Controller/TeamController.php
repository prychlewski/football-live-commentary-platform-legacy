<?php

namespace App\Controller;

use App\Model\Request\TeamRequestModel;
use App\Model\Response\TeamResponse;
use App\Service\TeamService;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamController extends BaseController
{
    private $teamService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        TeamService $teamService,
        ValidatorInterface $validator
    ) {
        $this->teamService = $teamService;
        $this->validator = $validator;
    }

    /**
     * @Route("/team", name="team_add",  methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $teamRequestModel = new TeamRequestModel(
            $request->request->get('name')
        );

        $validationErrors = $this->validator->validate($teamRequestModel);
        $this->handleErrors($validationErrors);

        $team = $this->teamService->createAndSaveTeam($teamRequestModel->getName());

        $response = new TeamResponse(
            $team->getId(),
            $team->getName()
        );

        return $this->view($response);
    }

    /**
     * @Route("/team/{id}", name="team_edit",  methods={"PATCH"})
     */
    public function editAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $teamRequestModel = new TeamRequestModel(
            $request->request->get('name')
        );

        $validationErrors = $this->validator->validate($teamRequestModel);
        $this->handleErrors($validationErrors);

        $team = $this->teamService->update($id, $teamRequestModel->getName());

        $response = new TeamResponse(
            $team->getId(),
            $team->getName()
        );

        return $this->view($response);
    }

    /**
     * @Route("/team/{id}", name="team_delete",  methods={"DELETE"})
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->teamService->delete($id);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/team/{id}", name="team_view",  methods={"GET"})
     */
    public function viewAction(int $id)
    {
        $team = $this->teamService->findTeam($id);

        $response = new TeamResponse(
            $team->getId(),
            $team->getName()
        );

        return $this->view($response);
    }
}
