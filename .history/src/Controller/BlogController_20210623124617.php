<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    //ArticleRepository $repo  -> va nous chercher es donnes dasn la base de données 
    public function index(ArticleRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class); // select de la base de données 
        $articles = $repo->findAll(); // lister toutt les articles

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles // les passer au twig 
        ]);
    }

    /**
     * @Route("/", name="home")
     */

    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/new", name="blog_create")
     */

    public function create(Request $request, EntityManagerInterface $manager)
    {
        $article = new Article();


        $article->setTitle('Titre de exemple');
        $article->setContent('contenu de exemple');



        $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getform();

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView() // create vienw cree un petit aspect d'affichage on va le passer a twig
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */

    public function show(ArticleRepository $repo, $id)
    {
        $article = $repo->find($id);

        return $this->render('blog/show.html.twig', [

            'article' => $article,

        ]);
    }
}
