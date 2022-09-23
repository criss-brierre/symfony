<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Entity\Users;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfilType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilController extends AbstractController
{
    private $articlesRepository;
    private $security;
    function __construct(ArticlesRepository $ArticlesReposit,Security $security,UserPasswordHasherInterface $userPasswordHasher) {
        $this->articlesRepository = $ArticlesReposit;
        $this->security = $security;
        
    }
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if($form->get('plainPassword')->getData() == htmlspecialchars($_POST["password2"]) ){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
            }
            
        }
        
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'ProfileForm' => $form->createView(),
        ]);      
    
        
        
    }
}
