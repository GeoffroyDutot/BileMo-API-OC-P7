<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
    public function getUsersByCompany(UserRepository $userRepository, CacheInterface $cache)
    {
        return $cache->get('users'.$this->getUser()->getId(), function (ItemInterface $item) use($userRepository) {
            $item->expiresAfter(30);

            return $this->json($userRepository->findBy(['company' => $this->getUser()->getId()]), 200, [], ['groups' => 'get:users']);
        });
    }

    /**
     * Get one user by company
     *
     * @Route("/api/users/{id}", name="user", methods={"GET"})
     */
    public function getUserByCompany(User $user)
    {
        if ($user->getCompany() === $this->getUser()) {
            return $this->json($user, 200, [], ['groups' => 'get:users']);
        } else {
            return $this->json(['success' => false, 'msg' => 'Unauthorized.'], 403);
        }
    }
}
