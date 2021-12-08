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
     * @Route("/index", name="index")
     */

    public function index(request $request): Response//utilisation de articleRepository

    {

        $em = $this->getDoctrine()->getManager();
        $articleRepository = $em->getRepository(Article::class);
        $listeArticlesDraft = $articleRepository->findAll();

        $article = new Article; // Génération d'un article prêt à l'emploi

        $form = $this->createForm(ArticleType::class, $article); // On crée un formulaire et on y lit $article
        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));

        }

        $i = 0;
        foreach ($listeArticlesDraft as $article) {

            $i++;

            $listeArticles = $articleRepository->findAll($i);
            $listeArticles = array_reverse($listeArticles);

        }
        return $this->render('index/index.html.twig',
            ['listeArticles' => $listeArticles,
                'form_article' => $form->createView(),
            ]);

    }

    //création de formulaire
    /**
     * @Route("/create-article", name="create_article")
     */
    public function createArticle(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('title', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => "Titre"])
            ->add('category', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('content', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class)
            ->add('valider', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            $data = $form->getData();
            $article = new Article;

            $article->setTitle($data['title']);
            $article->setCategory($data['category']);
            $article->setContent($data['content']);

            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));

        }

        return $this->render('index/form.html.twig', [
            'form_article' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create-article", name="create_article")
     */
    public function createArticleNew(Request $request)
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
    /*  Consigne:
Ordonner l'affichage des bulletins sur notre page index par groupes de dix, avec une frise de pages dynamiquement créée en bas de notre page pour consulter les autres bulletins.
Chaque page doit contenir dix articles: ainsi, la page doit contenir les bulletins 1 à 10, la page 2 les bulletins 11 à 20, etc. le tout dans l'ordre décroissant.
Récupérer toutes les pages dans le controller via un findAll(), puis utiliser les fonctions prédéfinies PHP pour manipuler le tableau et obtenir les entrées désirées selon la page parcourue.
Faire en sorte que la page actuellement visitée soit affichée dans l'URL de notre site web.
Consigne:
Ordonner l'affichage des bulletins sur notre page index par groupes de dix, avec une frise de pages dynamiquement créée en bas de notre page pour consulter les autres bulletins.
Chaque page doit contenir dix articles: ainsi, la page doit contenir les bulletins 1 à 10, la page 2 les bulletins 11 à 20, etc. le tout dans l'ordre décroissant.
Récupérer toutes les pages dans le controller via un findAll(), puis utiliser les fonctions prédéfinies PHP pour manipuler le tableau et obtenir les entrées désirées selon la page parcourue.
Faire en sorte que la page actuellement visitée soit affichée dans l'URL de notre site web.
 */
}