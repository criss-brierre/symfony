<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users;
use App\Entity\Articles;
use App\Repository\UsersRepository;
use App\Entity\Comments;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    private $usersRepository;
    function __construct(UserPasswordHasherInterface $pPasswordHasher,UsersRepository $UsersReposit ) {
        $this->passwordHasher = $pPasswordHasher;
        $this->usersRepository = $UsersReposit;
    }
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');
        $users = Array();
        $articles = Array();
        $Comments = Array();
        // $user = $this->usersRepository->find(126);
         
           for ($i = 0; $i <= 5; $i++) {
               $users[$i] = new Users();
               $nom = $faker->Name();
               $users[$i]->setUsername($nom);
               if($i % 2 == 0){
                    $users[$i]->setRoles(array("ROLE_ADMIN"));
                } else {
                    $users[$i]->setRoles(array("ROLE_USER"));
                }
               $users[$i]->setPassword(
                $this->passwordHasher->hashPassword(
                    $users[$i],
                    $nom
                ));
                $manager->persist($users[$i]);
                for($j =0; $j<=5; $j++){
                    $articles[$j] = new Articles();
                    $articles[$j]->setArticle($faker->sentence("490"));
                    $articles[$j]->setId_Users($users[$i]);
                    $articles[$j]->setDate($faker->datetime());
                    $manager->persist($articles[$j]);
                    for($k = 0;$k<=5;$k++){
                            $Comments[$k] = new Comments();
                            $Comments[$k]->setId_Article($articles[$j]);
                            $Comments[$k]->setId_Users($users[$i]);
                            $Comments[$k]->setCommentaire($faker->text());
                            $Comments[$k]->setDate($faker->datetime());
                            $manager->persist($Comments[$k]);
                        }
                }

               
           }

        $manager->flush();

    }
}
