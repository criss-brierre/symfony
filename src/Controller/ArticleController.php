<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Entity\Comments;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddCommentType;
use Knp\Component\Pager\PaginatorInterface;


class ArticleController extends AbstractController
{
    private $articlesRepository;
    private $security;
    function __construct(ArticlesRepository $ArticlesReposit,Security $security) {
        $this->articlesRepository = $ArticlesReposit;
        $this->security = $security;
    }
    #[Route('/article/id={id}', name: 'app_article')]
    public function index(int $id,EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {
        
    $Article = $this->articlesRepository->find($id);
    
    $Comments = new Comments();
    $form = $this->createForm(AddCommentType::class, $Comments);
    $form->handleRequest($request);
    
    $Comment = $Article->getLescommentaires()->toArray();

    if ($form->isSubmitted() && $form->isValid()) {
        $Comments->setId_Article($Article);
        $Comments->setId_Users($this->security->getUser());
        $date = new \DateTime('@'.strtotime('now'));
        $Comments->setDate($date);
       
        $entityManager->persist($Comments);
        array_push($Comment, $Comments);
    }

    $entityManager->flush();

    $Comment = array_reverse($Comment);
    $NombreElementParPage = 3;
        $Comments = $paginator->paginate(
            $Comment,
            $request->query->getInt('page', 1),
            $NombreElementParPage
        );
        $count= [];
        for ($i = 1;$i<= count($Comment) / $NombreElementParPage; $i++){
        $count[$i - 1] = $i;
    }     

        return $this->render('article/index.html.twig', [
            'Comments' => $Comments,
            'controller_name' => 'ArticleController',
            'Article' => $Article,
            'AddCommentType' => $form->createView(),
            'Count' => $count,
            
        ]);
    }
}
