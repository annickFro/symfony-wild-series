<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use App\Form\CommentType;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    /* see all programs
    ** ok
    */
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll() ;
        return $this->render('program/index.html.twig', [
            'programs'=>$programs]) ;
    }

    /*
    ** NEW
    */
    #[Route('/new/', name:'new')]
    public function new(Request $request, ProgramRepository $programRepository):Response
    {
        $program = new Program();

        // Create the form, linked with $program
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            // And redirect to a route that display the result

            $programRepository->save($program, true); 

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    /* see all the seasons for o program
    ** program nn
    */
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

    /*
    ** all episodes of a season for o program
    ** program nn season nn
    */    #[Route('/{programId}/season/{seasonId}', requirements: ['programId'=>'\d+'], methods: ['GET'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, 
        ProgramRepository $programRepository,
        SeasonRepository $seasonRepository,
        EpisodeRepository $episodeRepository,
        ): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);
        $episodes = $episodeRepository->findBy(['season' => $seasonId]) ;
        return $this->render('program/season_show.html.twig', [
            'website' => 'Wild Series',
            'program'=>$program,
            'season'=>$season,
            'programId' => $programId,
            'seasonId' => $seasonId,
            'episodes' => $episodes
            // 'seasons'=>$seasons
        ]) ;
    }

    /**
     * Detail episode
     */
    #[Route('/{programId}/season/{seasonId}/episode/{episodeId}', requirements: ['programId'=>'\d+'], methods: ['GET', 'POST'], name: 'episode_show')]
    public function showEpisode(int $programId, int $seasonId, int $episodeId, 
        EpisodeRepository $episodeRepository,
        Request $request,
        CommentRepository $commentRepository
        ): Response
    {
        $episode = $episodeRepository->findOneBy(['id' => $episodeId]) ;

        // add form to allow comments BEGIN =============================================
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid() ) {

            $comment->setAuthor($this->getUser()) ;
            $comment->setEpisode($episode) ;
            // var_dump($comment); die () ;
            $commentRepository->save($comment, true); 
            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }
        // add form to allow comments END   =============================================

        // load the comments
        $comments = $commentRepository->findBy(['episode' => $episodeId], ['id' => 'DESC']) ;

        return $this->render('program/episode_show.html.twig', [
            'website' => 'Wild Series',
            // 'program'=>$program,
            'programId' => $programId,
            'seasonId' => $seasonId,
            'episodeId' => $episodeId,
            'episode' => $episode,
            'form' => $commentForm->createView(),
            'comments' => $comments   
            // 'seasons'=>$seasons
        ]) ;
    }


}