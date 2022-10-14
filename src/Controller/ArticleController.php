<?php
// src/Controller/ArticleController.php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    public function index(ArticleRepository $articleRepository): Response
    {
        $data = [];
        $data['name'] = "Muntasir";
        $data['articles'] = $articleRepository->findAll();
        return $this->render('articles/index.html.twig', $data);
    }

    public function createArticle(ManagerRegistry $doctrine, Request $request): Response
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Create Article', 'attr' => ['class' => 'form-control btn btn-primary mt-3'],])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $articleManager = $doctrine->getManager();
            $articleManager->persist($article);
            $articleManager->flush();
            return $this->redirectToRoute('viewArticle', ['id' => $article->getId()]);
        }

        return $this->renderForm('articles/articleForm.html.twig', [
            'form' => $form,
            'title' => 'Create a new Article!',
        ]);
    }

    public function viewArticle(ArticleRepository $articleRepository, int $id): Response
    {
        $data = [];
        $data['article'] = $articleRepository->find($id);
        if(!$data['article']) {
            return $this->render('articles/invalid.html.twig');
        } else {
            return $this->render('articles/article.html.twig', $data);
        }

    }

    public function updateArticle(ArticleRepository $articleRepository, ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $article = $articleRepository->find($id);
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Update Article', 'attr' => ['class' => 'form-control btn btn-primary mt-3'],])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $articleManager = $doctrine->getManager();
            $article = $form->getData();
            $articleManager->flush();
            return $this->redirectToRoute('viewArticle', ['id' => $article->getId()]);
        }

        return $this->renderForm('articles/articleForm.html.twig', [
            'form' => $form,
            'title' => 'Update Article: '.$article->getTitle(),
        ]);
    }

    public function deleteArticle(ArticleRepository $articleRepository, int $id): Response
    {
        $article = $articleRepository->find($id);
        if(!$article) {
            return $this->render('articles/invalid.html.twig');
        } else {
            $articleRepository->remove($article, true);
            return $this->redirectToRoute('root');
        }

    }
}