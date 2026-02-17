<?php

namespace App\Controller;

use App\Form\EventSearchType;
use App\Models\EventSearch;
use App\Util\EventSearchApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{
    #[Route('/events', name: 'main_events', methods: ['GET','POST'])]
    public function events(Request $request, EventSearchApi $eventSearchApi): Response
    {
        $eventSearch = new EventSearch();
        $eventSearch->dateEvent = new \DateTimeImmutable();

        $eventForm = $this->createForm(EventSearchType::class, $eventSearch);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $events = $eventSearchApi->search($eventSearch);
        } else {
            $events = [];
        }

        return $this->render('events/list.html.twig', [
            'events' => $events,
            'eventForm' => $eventForm->createView(),
        ]);
    }
}
