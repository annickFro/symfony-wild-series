<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;


#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll() ;
        // var_dump($categories) ;
        return $this->render('category/index.html.twig', ['website' => 'Wild Series',
        'categories'=>$categories]) ;
    }

    #[Route('/{categoryName}/', name: 'show')]
    public function show(string $categoryName, 
        CategoryRepository $categoryRepository,
        ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy  (['name'=> $categoryName]) ;
        if (!$category) {
            throw $this->createNotFoundException('The category does not exist');
        } 
        // récupère toutes les séries de cette catégorie.
        // max 3 et id décroissant
        $programs = $programRepository->findBy(
            ['category'=> (int)$category->getId()],
            ['id'=> 'DESC'],
            3       // LIMIT
        );

        return $this->render('category/show.html.twig', [
            'category' => $categoryName,
            'programs' => $programs
        ]) ;
    }

}