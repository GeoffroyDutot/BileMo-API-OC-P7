<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products", methods={"GET"})
     */
    public function getProducts(PhoneRepository $phoneRepository)
    {
        return $this->json($phoneRepository->findAll(), 200, [], ['groups' => 'get:products']);
    }
}
