<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\DataFixtures\Faker\Factory;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();

            $category->setTitle("Title de category $i")
                ->setDescription("Description de Category $i");

            $manager->persist($category);

            for ($g = 1; $g <= mt_rand(4, 6); $g++) {
                $article = new Article();
                $article->setTitle("Titre de l'article N $g")
                    ->setContent("<p> Contenu de l'article Numero $g </p>")
                    ->setImage("http://via.placeholder.com/350")
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setCategory($category);

                $manager->persist($article);
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();


                    $comment->setAuthor("Authot numero $k")
                        ->setContent("Content numero $k")
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setArticle($article);

                    $manager->persist($comment);
                }
            }
            $manager->flush();
        }
    }
}
