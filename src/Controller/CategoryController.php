<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;


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

    /*
    ** NEW
    */
    #[Route('/new/', name:'new')]
    public function new(Request $request, CategoryRepository $categoryRepository):Response
    {
        $category = new Category() ;

        // Create the form, linked with $category
        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // For example : persiste & flush the entity
            // And redirect to a route that display the result
            $categoryRepository->save($category, true); 

            // Redirect to categories list
            return $this->redirectToRoute('category_index');
        }

        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
        ]);
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