<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Articles;
use App\Entity\Comment;
use App\Repository\ArticlesRepository;
use App\Form\ArticleType;

class BlogController extends AbstractController
{

  /**
   * @Route("/", name="index")
   */
  public function index()
  {
      return $this->render('blog/index.html.twig', [
          'controller_name' => 'la page d\'accueil',
          'assets' => ''
      ]);
  }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog(ArticlesRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->findAll();

        return $this->render('blog/blog.html.twig', [
            'controller_name' => 'la page blog',
            'assets' => '',
            'articles' => $articles
        ]);
    }

    /**
    * @Route("/blog/new", name="blog_create")
    * @Route("/blog/{id}/edit", name="blog_edit")
    */
    public function create(Articles $article = null, Request $request, ObjectManager $manager)
    {
      if(!$article){
        $article = new Articles();
      }

      $formCreateArticle = $this->createForm(ArticleType::class, $article);

      $formCreateArticle->handleRequest($request);

      if($formCreateArticle->isSubmitted() && $formCreateArticle->isValid()) {
        if(!$article->getId()){
          $article->setCreatedAt(new \DateTime);
        }

        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('article', ['id' => $article->getId()]);
      }


      return $this->render('blog/create.html.twig', [
        'controller_name' => 'la page de création d\'article',
        'assets' => '../',
        'formArticle' => $formCreateArticle->createView(),
        'editMode' => $article->getId() !== null
      ]);
    }

    /**
     * @Route("/blog/{id}", name="article")
     */
    public function article(Articles $article, Request $request, ObjectManager $manager) //$article PARAM CONVERTER -> ROUTE TO VAR
    {
        $comment = new Comment();

        $formCreateComment = $this->createFormBuilder($comment)
                            ->add('author', null)
                            ->add('content', null)
                            ->getForm();

        $formCreateComment->handleRequest($request);

        if($formCreateComment->isSubmitted() && $formCreateComment->isValid()) {
            if($article->getId()){
            $comment->setCreatedAt(new \DateTime);
            $comment->setArticle($article);
            }

            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render('blog/article.html.twig', [
            'controller_name' => 'l\'article n°'.$article->getId(),
            'assets' => '../',
            'formComment' => $formCreateComment->createView(),
            'article' => $article
        ]);
    }

}
