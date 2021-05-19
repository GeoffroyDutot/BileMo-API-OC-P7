<?php

namespace App\Controller;

use App\Entity\Phone;
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
     * @Route("/api/products", name="products", methods={"GET"})
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

    /**
     * Get one product by it's id
     *
     * @Route("/api/product/{id}", name="product", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Return one product by it's id",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"get:products"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of product",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="products")
     */
    public function getProductById(Phone $phone, CacheInterface $cache)
    {
        return $cache->get('product'.$phone->getId(), function (ItemInterface $item) use($phone) {
            $item->expiresAfter(30);

            return $this->json($phone, 200, [], ['groups' => 'get:products']);
        });
    }
}
