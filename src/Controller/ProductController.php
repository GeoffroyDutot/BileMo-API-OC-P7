<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products", methods={"GET"})
     */
    public function getProducts(PhoneRepository $phoneRepository, CacheInterface $cache)
    {
        return $cache->get('products', function (ItemInterface $item) use($phoneRepository) {
            $item->expiresAfter(30);

            return $this->json($phoneRepository->findBy([], ['releaseDate' => 'DESC']), 200, [], ['groups' => 'get:products']);
        });
    }
}
