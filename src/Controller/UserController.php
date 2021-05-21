<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    /**
     * List of users by company
     *
     * @Route("/api/users", name="users", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns list of users by company",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"get:users"}))
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function getUsersByCompany(UserRepository $userRepository)
    {
        return $this->json($userRepository->findBy(['company' => $this->getUser()->getId()]), 200, [], ['groups' => 'get:users']);
    }
}
