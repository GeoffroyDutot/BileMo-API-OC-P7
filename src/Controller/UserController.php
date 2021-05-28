<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;
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
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of user",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Return one user by company",
     *     @Model(type=User::class, groups={"get:users"})
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function getUserByCompany(User $user, CacheInterface $cache)
    {
        return $cache->get('user'.$user->getId().'-'.$this->getUser()->getId(), function (ItemInterface $item) use($user) {
            $item->expiresAfter(30);

            if ($user->getCompany() === $this->getUser()) {
                return $this->json($user, 200, [], ['groups' => 'get:users']);
            } else {
                return $this->json(['success' => false, 'msg' => 'Unauthorized.'], 403);
            }
        });
    }

    /**
     * Add a new user by a company
     *
     * @Route("/api/users", name="add_user", methods={"POST"})
     * @OA\RequestBody(
     *     description="User to add",
     *     required=true,
     *     @Model(type=User::class, groups={"get:users"})
     *     )
     * @OA\Response(
     *     response=201,
     *     description="Success - Return User added.",
     *     @Model(type=User::class, groups={"get:users"})
     * )
     * @OA\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * @OA\Response(
     *     response=500,
     *     description="Syntax Error - Internal Error"
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function addUserByCompany(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        if (empty($user->getDateRegistration())) {
            $user->setDateRegistration(new \DateTime('now'));
        }

        $user->setCompany($this->getUser());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201, [], ['groups' => 'get:users']);
    }

    /**
     * Delete an user by a company
     *
     * @Route("/api/users/{id}", name="delete_user", methods={"DELETE"})
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of user to delete",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Success, User deleted."
     * )
     * @OA\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * @OA\Response(
     *     response=403,
     *     description="Unauthorized."
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found."
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function deleteUserByCompany(User $user, EntityManagerInterface $entityManager)
    {
        if ($user->getCompany() === $this->getUser()) {
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->json(['success' => true, 'msg' => 'Success, User deleted.'], 200);
        } else {
            return $this->json(['success' => false, 'msg' => 'Unauthorized.'], 403);
        }
    }
}
