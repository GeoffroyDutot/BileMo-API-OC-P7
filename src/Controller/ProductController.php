<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductController extends AbstractController
{
    /**
     * List all products
     *
     * @Route("/api/products", name="products", methods={"GET"})
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     required=false,
     *     description="Limit of products to return",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="offset",
     *     in="query",
     *     required=false,
     *     description="Offset of products to return",
     *     @OA\Schema(type="integer")
     * )
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
    public function getProducts(Request $request, PhoneRepository $phoneRepository, CacheInterface $cache)
    {
        // Limit of products to return default 10
        $limit = $request->get('limit') ?? 10;

        // Offset of products to return default 0
        $offset = $request->get('offset') ?? 0;

        return $cache->get('products', function (ItemInterface $item) use($phoneRepository, $limit, $offset) {
            $item->expiresAfter(30);

            return $this->json($phoneRepository->findBy([], ['releaseDate' => 'DESC'], $limit, $offset), 200, [], ['groups' => 'get:products']);
        });
    }

    /**
     * Get one product by it's id
     *
     * @Route("/api/products/{id}", name="product", methods={"GET"})
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of product",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Return one product by it's id",
     *     @Model(type=Phone::class, groups={"get:products"})
     * )
     * @OA\Response(
     *     response=404,
     *     description="Product not found."
     * )
     * @OA\Tag(name="products")
     */
    public function getProductById(Phone $phone, CacheInterface $cache)
    {
        return $this->json($phone, 200, [], ['groups' => 'get:products']);die();
        return $cache->get('product'.$phone->getId(), function (ItemInterface $item) use($phone) {
            $item->expiresAfter(30);

            return $this->json($phone, 200, [], ['groups' => 'get:products']);
        });
    }
}
