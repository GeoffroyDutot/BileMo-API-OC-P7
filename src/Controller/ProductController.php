<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductController extends AbstractController
{
    /**
     * List all products
     *
     * @Route("/products", name="products", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns list of products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"get:products"}))
     *     )
     * )
     * @OA\Tag(name="products")
     */
    public function getProducts(PhoneRepository $phoneRepository, CacheInterface $cache)
    {
        return $cache->get('products', function (ItemInterface $item) use($phoneRepository) {
            $item->expiresAfter(30);

            return $this->json($phoneRepository->findBy([], ['releaseDate' => 'DESC']), 200, [], ['groups' => 'get:products']);
        });
    }
}
