<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // 1ère figure -----------------------------------------------------------------
        $category = new Category();
        $category->setName("NoseGrab2");         // on crée la catégorie
        $manager->persist($category);
        
        $noseGrab = new Article();      // on crée l'article en récupérant la catégorie
        $noseGrab 
            ->setCategorie($category)
            ->setTitle("NoseGrab2")
            ->setContent("Cette figure consiste à attraper l'avant du snowboard.")
            ->setImage("nosegrab.jpg")
            ->setCreatedAt(new \DateTime())
            ->setSlug("nosegrab2");
        
        $manager->persist($noseGrab);   // on fait persister l'article
        
        // 2ème figure -----------------------------------------------------------------
        $category = new Category();
        $category->setName("TailGrab2");         // on crée la catégorie
        $manager->persist($category);
        
        $tailGrab = new Article();      // on crée l'article en récupérant la catégorie
        $tailGrab 
            ->setCategorie($category)
            ->setTitle("TailGrab2")
            ->setContent("Cette figure consiste à attraper l'arrière de la planche.")
            ->setImage("tailgrab.jpg")
            ->setCreatedAt(new \DateTime())
            ->setSlug("tailgrab2");
        
        $manager->persist($tailGrab);   // on fait persister l'article
        
        // 3ème figure -----------------------------------------------------------------
        $category = new Category();
        $category->setName("Flip2");
        $manager->persist($category);
        
        $flip = new Article();      // on crée l'article en récupérant la catégorie
        $flip 
            ->setCategorie($category)
            ->setTitle("Flip2")
            ->setContent("La figure consiste en position avant à passer sur toute la longueur d'une barre.")
            ->setImage("frontflip.jpg")
            ->setCreatedAt(new \DateTime())
            ->setSlug("flip2");
        
        $manager->persist($flip);   // on fait persister l'article
        
        // 4ème figure -----------------------------------------------------------------
        $category = new Category();
        $category->setName("Rotation2");
        $manager->persist($category);
        
        $rotation = new Article();      // on crée l'article en récupérant la catégorie
        $rotation 
            ->setCategorie($category)
            ->setTitle("Rotation2")
            ->setContent("La figure consiste à effectuer 1 tour complet en l'air..")
            ->setImage("front360.jpg")
            ->setCreatedAt(new \DateTime())
            ->setSlug("rotation2");
        
        $manager->persist($rotation);   // on fait persister l'article
        
        // 5ème figure -----------------------------------------------------------------
        $category = new Category();
        $category->setName("OneFoot2");
        $manager->persist($category);
        
        $onefoot = new Article();      // on crée l'article en récupérant la catégorie
        $onefoot 
            ->setCategorie($category)
            ->setTitle("OneFoot2")
            ->setContent("Cela consiste à utiliser sa planche avec un seul pied fixé et à réaliser les figures dans ces conditions.")
            ->setImage("onefoottricks.jpg")
            ->setCreatedAt(new \DateTime())
            ->setSlug("onefoot2");
        
        $manager->persist($onefoot);   // on fait persister l'article
        
        
        // Si on veut des données fictives on utilisera le code ci-dessous 
        /*for($j = 1; $j <= 2; $j++) {
            $category = new Category();
            $category->setName("Catégorie N°".$j);
            $manager->persist($category);
            
            for($i=1; $i <= 5; $i++) {
                $article = new Article();
                $article -> setCategorie($category)
                         -> setTitle("Nom de la figure $i")
                         -> setContent("Description de la figure $i")
                         -> setImage("http://placehold-it/500x400")
                         -> setCreatedAt(new \DateTime())
                         -> setSlug("nom-de-la-figure-$i");
        
                $manager->persist($article);
            }
        } */
        
        
        $manager->flush();      // on injecte toutes les informations en base de données
    }
}
