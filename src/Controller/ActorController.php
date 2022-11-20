<?php

namespace App\Controller;

use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
{
    #[Route('/actor', name: 'app_actor')]
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll() ;
        return $this->render('actor/index.html.twig', [
            'controller_name' => 'ActorController',
            'actors' => $actors,
        ]);
    }

    #[Route('/actor/{id}', name: 'app_actor_show')]
    public function show(
        int $id,
        ActorRepository $actorRepository
    ): Response
    {
        $actor = $actorRepository->findOneBy(['id' => $id]) ;
        return $this->render('actor/show.html.twig', [
            'controller_name' => 'ActorController',
            'actor' => $actor,
        ]);
    }
}
