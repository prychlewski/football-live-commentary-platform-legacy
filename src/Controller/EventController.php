<?php

namespace App\Controller;

use App\Model\Request\EventGoalRequestModel;
use App\Model\Request\EventRequestModel;
use App\Model\Response\EventResponse;
use App\Service\EventService;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends BaseController
{
    private $eventService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        EventService $eventService,
        ValidatorInterface $validator
    ) {
        $this->eventService = $eventService;
        $this->validator = $validator;
    }

    /**
     * @Route("/event", name="event_add",  methods={"POST"})
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventRequestModel = new EventRequestModel(
            $request->request->get('hostTeamId'),
            $request->request->get('guestTeamId'),
            new \DateTime($request->request->get('date'))
        );

        $validationErrors = $this->validator->validate($eventRequestModel);
        $this->handleErrors($validationErrors);

        $event = $this->eventService->createAndSaveEvent(
            $eventRequestModel->getHostTeamId(),
            $eventRequestModel->getGuestTeamId(),
            $eventRequestModel->getDate()
        );

        $response = new EventResponse(
            $event->getId(),
            $eventRequestModel->getHostTeamId(),
            $eventRequestModel->getGuestTeamId(),
            $eventRequestModel->getDate()
        );

        return $this->view($response);
    }

    /**
     * @Route("/event/{id}", name="event_edit",  methods={"PATCH"})
     */
    public function editAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventRequestModel = new EventRequestModel(
            $request->request->get('hostTeamId'),
            $request->request->get('guestTeamId'),
            new \DateTime($request->request->get('date'))
        );

        $validationErrors = $this->validator->validate($eventRequestModel);
        $this->handleErrors($validationErrors);

        $event = $this->eventService->update(
            $id,
            $eventRequestModel->getHostTeamId(),
            $eventRequestModel->getGuestTeamId(),
            $eventRequestModel->getDate()
        );

        $response = new EventResponse(
            $event->getId(),
            $event->getHostTeam()->getId(),
            $event->getGuestTeam()->getId(),
            $event->getDate(),
            $event->getHostPoints(),
            $event->getGuestPoints()
        );

        return $this->view($response);
    }

    /**
     * @Route("/event/{id}", name="event_delete",  methods={"DELETE"})
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->eventService->delete($id);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/event/{id}", name="event_view",  methods={"GET"})
     */
    public function viewAction(int $id)
    {
        $event = $this->eventService->findEvent($id);

        $response = new EventResponse(
            $event->getId(),
            $event->getHostTeam()->getId(),
            $event->getGuestTeam()->getId(),
            $event->getDate(),
            $event->getHostPoints(),
            $event->getGuestPoints()
        );

        return $this->view($response);
    }

    /**
     * @Route("/event/{id}/goal", name="event_goal",  methods={"PATCH"})
     */
    public function scoreAGoalAction(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventGoalRequestModel = new EventGoalRequestModel(
            $request->request->get('teamId')
        );

        $validationErrors = $this->validator->validate($eventGoalRequestModel);
        $this->handleErrors($validationErrors);

        $this->eventService->scoreAGoal(
            $id,
            $eventGoalRequestModel->getTeamId()
        );

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
