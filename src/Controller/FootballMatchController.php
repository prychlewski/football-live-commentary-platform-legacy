<?php

namespace App\Controller;

use App\Model\Request\GoalRequestModel;
use App\Model\Request\FootballMatchRequestModel;
use App\Model\Response\FootballMatchResponse;
use App\Service\FootballMatchService;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FootballMatchController extends BaseController
{
    private $footballMatchService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        FootballMatchService $footballMatchService,
        ValidatorInterface $validator
    ) {
        $this->footballMatchService = $footballMatchService;
        $this->validator = $validator;
    }

    /**
     * @Route("/football-match", name="football_match_add",  methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $footballMatchRequestModel = new FootballMatchRequestModel(
            $request->request->get('hostTeamId'),
            $request->request->get('guestTeamId'),
            new \DateTime($request->request->get('date'))
        );

        $validationErrors = $this->validator->validate($footballMatchRequestModel);
        $this->handleErrors($validationErrors);

        $footballMatch = $this->footballMatchService->createAndSaveFootballMatch(
            $footballMatchRequestModel->getHostTeamId(),
            $footballMatchRequestModel->getGuestTeamId(),
            $footballMatchRequestModel->getDate()
        );

        $response = new FootballMatchResponse(
            $footballMatch->getId(),
            $footballMatchRequestModel->getHostTeamId(),
            $footballMatchRequestModel->getGuestTeamId(),
            $footballMatchRequestModel->getDate()
        );

        return $this->view($response);
    }

/**
 * @Route("/football-match/{id}", name="football_match_edit",  methods={"PATCH"})
 */
public function editAction(Request $request, int $id)
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $footballMatchRequestModel = new FootballMatchRequestModel(
        $request->request->get('hostTeamId'),
        $request->request->get('guestTeamId'),
        new \DateTime($request->request->get('date'))
    );

    $validationErrors = $this->validator->validate($footballMatchRequestModel);
    if ($validationErrors->count() > 0) {
        $errors = [];
        /** @var ConstraintViolation $error */
        foreach ($validationErrors as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        throw new ModelValidationException($errors);
    }

    $footballMatch = $this->footballMatchService->update(
        $id,
        $footballMatchRequestModel->getHostTeamId(),
        $footballMatchRequestModel->getGuestTeamId(),
        $footballMatchRequestModel->getDate()
    );

    $response = new FootballMatchResponse(
        $footballMatch->getId(),
        $footballMatch->getHostTeam()->getId(),
        $footballMatch->getGuestTeam()->getId(),
        $footballMatch->getDate(),
        $footballMatch->getHostPoints(),
        $footballMatch->getGuestPoints()
    );

    return $this->view($response);
}

    /**
     * @Route("/football-match/{id}", name="football_match_delete",  methods={"DELETE"})
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->footballMatchService->delete($id);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/football-match/{id}", name="football_match_view",  methods={"GET"})
     */
    public function viewAction(int $id)
    {
        $footballMatch = $this->footballMatchService->findFootballMatch($id);

        $response = new FootballMatchResponse(
            $footballMatch->getId(),
            $footballMatch->getHostTeam()->getId(),
            $footballMatch->getGuestTeam()->getId(),
            $footballMatch->getDate(),
            $footballMatch->getHostPoints(),
            $footballMatch->getGuestPoints()
        );

        return $this->view($response);
    }

    /**
     * @Route("/football-match/{id}/goal", name="football_match_goal",  methods={"PATCH"})
     */
    public function scoreAGoalAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $goalRequestModel = new GoalRequestModel(
            $request->request->get('teamId')
        );

        $validationErrors = $this->validator->validate($goalRequestModel);
        $this->handleErrors($validationErrors);

        $this->footballMatchService->scoreAGoal($id, $goalRequestModel->getTeamId());

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
