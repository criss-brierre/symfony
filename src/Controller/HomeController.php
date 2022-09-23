<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $articlesRepository;
    function __construct(ArticlesRepository $ArticlesReposit ) {
        $this->articlesRepository = $ArticlesReposit;
    }
    #[Route('/', name: 'app_home')]
    
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $Article = $this->articlesRepository->findBy([],["date" =>"desc"]);
        $NombreElementParPage = 3;
        $Articles = $paginator->paginate(
            $Article,
            $request->query->getInt('page', 1),
            $NombreElementParPage
        );
        $count;
        for ($i = 1;$i<= count($Article) / $NombreElementParPage; $i++){
        $count[$i - 1] = $i;
    }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'Articles' => $Articles,
            'Count' => $count,
        ]);
    }
}
