<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager(); // Un appel de l'Entity Manager
        $articleRepository = $em->getRepository(Article::class); // Récupération du Repository de Article
        $categories = $articleRepository->findEachCategory(); // Récupération des différentes catégories
        $listeArticles = array_reverse($articleRepository->findAll()); // Récupération de tous les articles de notre BDD
        $articleCount = count($listeArticles);
        $articlePages = ceil($articleCount / 10);
        $articleSelection = [];

        $page = 1;
        $i = ($page - 1) * 10;
        for ($j = 0; $j < 10; $j++) {
            if (isset($listeArticles[($i + $j)])) {
                array_push($articleSelection, $listeArticles[($i + $j)]);
            } else {
                break;
            }

        }

        $article = new Article; // Génération d'un article prêt à l'emploi
        $form = $this->createForm(ArticleType::class, $article); // On crée un formulaire et on y lit $article
        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'articlePages' => $articlePages,
            'listeArticles' => $articleSelection,
            'form_article' => $form->createView(),
        ]);
    }

    /**
     * @Route("/page/{pageNumber}", name="page_index")
     */
    public function pageIndex(Request $request, $pageNumber = false): Response
    {
        if (!is_int($pageNumber) || ($pageNumber <= 0)) { //redirige vers l'index en cas d'URL non pertinente
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager(); // Un appel de l'Entity Manager
        $articleRepository = $em->getRepository(Article::class); // Récupération du Repository de Article
        $categories = $articleRepository->findEachCategory(); // Récupération des différentes catégories
        $listeArticles = $articleRepository->findAll(); // Récupération de tous les articles de notre BDD
        $listeArticles = array_reverse($listeArticles);
        $articleCount = count($listeArticles);
        $articlePages = ceil($articleCount / 10);
        $articleSelection = [];

        $i = ($pageNumber - 1) * 10;
        for ($j = 0; $j < 10; $j++) {
            if (isset($listeArticles[($i + $j)])) {
                array_push($articleSelection, $listeArticles[($i + $j)]);
            } else {
                break;
            }

        }

        $article = new Article; // Génération d'un article prêt à l'emploi
        $form = $this->createForm(ArticleType::class, $article); // On crée un formulaire et on y lit $article
        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'articlePages' => $articlePages,
            'listeArticles' => $articleSelection,
            'form_article' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categorie/{category}", name="category")
     */
    public function category(Request $request, $category): Response
    {
        $em = $this->getDoctrine()->getManager(); // Un appel de l'Entity Manager
        $articleRepository = $em->getRepository(Article::class); // Récupération du Repository de Article
        $categories = $articleRepository->findEachCategory(); // Récupération des différentes catégories

        $listeArticles = $articleRepository->findByCategory($category); // Récupération des articles pertinents de notre BDD

        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'listeArticles' => $listeArticles,
        ]);
    }

    /**
     * @Route("/create-article", name="create_article")
     */
    public function createArticle(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); // On récupère l'Entity Manager
        $article = new Article; // Génération d'un article prêt à l'emploi

        $form = $this->createForm(ArticleType::class, $article); // On crée un formulaire et on y lit $article
        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('index/form.html.twig', [
            'form_article' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create-article-old", name="create_article_old")
     */
    public function createArticleOld(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); // On récupère l'Entity Manager

        $form = $this->createFormBuilder()
            ->add('title', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => "Titre"])
            ->add('category', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('content', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, ['label' => 'Contenu'])
            ->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, ['label' => "Mot de passe"])
            ->add('valider', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['attr' => ["class" => 'btn-success', "style" => "margin-top: 5px; margin-bottom: 5px"]])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            $data = $form->getData();
            if ($data['password'] == "12345") {
                $article = new Article;

                $article->setTitle($data['title']);
                $article->setCategory($data['category']);
                $article->setContent($data['content']);

                $em->persist($article);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('index/form.html.twig', [
            'form_article' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{articleId}", name="delete_article")
     */
    public function deleteConfirm(Request $request, $articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $blogOptionRepository = $em->getRepository(\App\Entity\BlogOptions::class);

        $formDelete = $this->createFormBuilder()
            ->add('value', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, ['label' => "Value"])
            ->add('supprimer', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['attr' => ["class" => 'btn-danger', "style" => "margin-top: 5px; margin-bottom: 5px"]])
            ->getForm()
        ;

        $formDelete->handleRequest($request);
        if ($request->isMethod('post') && $formDelete->isValid()) {
            $data = $formDelete->getData();
            $blogOptionPassword = $blogOptionRepository->findByDatakey('password');
            $blogOptionPassword = $blogOptionPassword[0]->getValue();
            if ($data['value'] == $blogOptionPassword) {
                $articleRepository = $em->getRepository(Article::class);
                $article = $articleRepository->findOneById($articleId);
                if ($article) {
                    $em->remove($article);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('index/delete-confirm.html.twig', [
            'formDelete' => $formDelete->createView(),
        ]);
    }

}