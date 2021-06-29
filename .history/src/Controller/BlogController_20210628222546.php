<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\VarDumper\VarDumper;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    //ArticleRepository $repo  -> va nous chercher es donnes dasn la base de données 
    public function index(ArticleRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class); // select de la base de données 
        $articles = $repo->findAll(); // lister toutt les articles

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles, // les passer au twig 
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
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function form(Article $article = null,    Request $request, EntityManagerInterface $manager)
    {
        if (!$article) {
            $article = new Article();
        }

        /*    $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getform(); */

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTimeImmutable());
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(), // create vienw cree un petit aspect d'affichage on va le passer a twig
            'editMode' => $article->getId() !== null,

        ]);
    }

    /**
     * @Route("/blog/comment", name="blog_comment")
     */
    public function createComment(Comment $comment = null,  Request $request, EntityManagerInterface $manager)
    {

        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render('blog/comment.html.twig', [
            'formComment' => $formComment->createView()
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
