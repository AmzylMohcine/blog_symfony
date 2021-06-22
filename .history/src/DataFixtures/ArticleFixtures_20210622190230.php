<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitle("Titre de l'article N $i")
            ->setContent("<p> Contenu de l'article Numero $i </p>")
            ->setImage("https://via.placeholder.com/350")
            $article->setCreatedAt(new \DateTimeImmutable());

            $manager->flush($article);
        }

        $manager->flush($article);
    }
}
