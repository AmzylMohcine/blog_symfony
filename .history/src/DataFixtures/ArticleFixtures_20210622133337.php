<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle("Titre de l'article N $i");
            $article ->setContent("<p> Contenu de l'article Numero $i</p>");
            $article -> setImage
        }

        $manager->flush();
    }
}
