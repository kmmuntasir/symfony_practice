<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/',
        name: 'root',
        methods: ['GET'],
    )]
    public function index(): Response
    {
//        return new Response("<html><body><h1>Hello World</h1></body></html>");
        return $this->render('articles/index.html.twig');
    }
    #[Route('/{slug}',
        name: 'slug',
        methods: ['GET'],
    )]
    public function slug(string $slug): Response
    {
        return new Response("<html><h1>{$slug}</h1></html>");
    }
}