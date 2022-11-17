<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;


#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll() ;
        // var_dump($programs) ;
        return $this->render('program/index.html.twig', ['website' => 'Wild Series',
        'programs'=>$programs]) ;
    }

    // #[Route('/{id}/', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'show')]
    // public function show(int $id, ProgramRepository $programRepository): Response
    // {
    //     $program = $programRepository->findOneBy(['id' => $id]);
    //     return $this->render('program/show.html.twig', [
    //         'website' => 'Wild Series',
    //         'id' => $id,
    //         'program'=>$program
    //     ]) ;
    // }

    #[Route('/{id}/', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'show')]
    public function show(int $id, 
        ProgramRepository $programRepository,
        SeasonRepository $seasonRepository,
        ): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        $seasons = $seasonRepository->findBy(['program' => $id]) ;
        return $this->render('program/show.html.twig', [
            'website' => 'Wild Series',
            'id' => $id,
            'program'=>$program,
            'seasons'=>$seasons
        ]) ;
    }

    #[Route('/{programId}/seasons/{seasonId}', requirements: ['programId'=>'\d+'], methods: ['GET'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, 
        ProgramRepository $programRepository,
        // SeasonRepository $seasonRepository,
        EpisodeRepository $episodeRepository,
        ): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        // $seasons = $seasonRepository->findBy(['program' => $programId]) ;
        $episodes = $episodeRepository->findBy(['season' => $seasonId]) ;
        return $this->render('program/season_show.html.twig', [
            'website' => 'Wild Series',
            'program'=>$program,
            'programId' => $programId,
            'seasonId' => $seasonId,
            'episodes' => $episodes
            // 'seasons'=>$seasons
        ]) ;
    }
    
}