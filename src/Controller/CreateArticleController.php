<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Entity\Articles;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Form\AddArticleType;
use Symfony\Component\HttpFoundation\Request;


class CreateArticleController extends AbstractController
{
    private $articlesRepository;
    private $security;
    private $datets;
    function __construct(ArticlesRepository $ArticlesReposit,Security $security) {
        $this->articlesRepository = $ArticlesReposit;
        $this->security = $security;
        
    }
    
    #[Route('/create/article', name: 'app_create_article')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $Article = new Articles();
        $form = $this->createForm(AddArticleType::class, $Article);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $Article->setId_Users($this->security->getUser());
            $date = new \DateTime('@'.strtotime('now'));
            $Article->setDate($date);
           
            $entityManager->persist($Article);
            $entityManager->flush();
        }
        
       
    
    
        
        return $this->render('create_article/index.html.twig', [
            'controller_name' => 'CreateArticleController',
            'createArticleForm' => $form->createView(),
        ]);
    }
   
}
