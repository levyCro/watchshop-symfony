<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProductRepository;

class ProductsController extends AbstractController
{
    #[Route('/', name: 'home')] 
    public function homepage(ProductRepository $repo): Response
    {
        $watches = $repo->findBy([]);
        return $this->render('homepage.html.twig', [
            'watches' => $watches
        ]);
    }

    /**
     * @Route("/products/{id}")
     */
    public function details($id, Request $request, ProductRepository $repo, SessionInterface $session): Response
    {
        $watch = $repo->find($id);

        if ($watch === null) {
            throw $this->createNotFoundException('Could not find product');
        }
        // adding to basket list
        $basket = $session->get('basket', []);
        

        if ($request->isMethod('POST')) {
            $basket[$watch->getId()] = $watch;
            $session->set('basket', $basket);
        }
        $isInBasket = array_key_exists($watch->getId(), $basket);
        

        return $this->render('details.html.twig', [
            'watch' => $watch,
            'inBasket' => $isInBasket
        ]);
    }
        
}
