<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class UserController extends AbstractController
{
    /**
     * List of users by company
     *
     * @Route("/api/users", name="users", methods={"GET"})
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     required=false,
     *     description="Limit of users to return",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="offset",
     *     in="query",
     *     required=false,
     *     description="Offset of users to return",
     *     @OA\Schema(type="integer")
     * )
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
    public function getUsersByCompany(Request $request, UserRepository $userRepository, CacheInterface $cache)
    {
        $limit = $request->get('limit') ?? 10;
        $offset = $request->get('offset') ?? 0;

        return $cache->get('users'.$this->getUser()->getId(), function (ItemInterface $item) use($userRepository, $limit, $offset) {
            $item->expiresAfter(30);

            return $this->json($userRepository->findBy(['company' => $this->getUser()->getId()], ['dateRegistration' => 'DESC'], $limit, $offset), 200, [], ['groups' => 'get:users']);
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
    public function getUserByCompany(User $user, CacheInterface $cache)
    {
        return $cache->get('user'.$user->getId().'-'.$this->getUser()->getId(), function (ItemInterface $item) use($user) {
            $item->expiresAfter(30);

            if ($user->getCompany() === $this->getUser()) {
                return $this->json($user, 200, [], ['groups' => 'get:users']);
            } else {
                throw new AccessDeniedHttpException();
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
     *     @Model(type=User::class, groups={"write:users"})
     * )
     * @OA\Response(
     *     response=201,
     *     description="Success - Return User added.",
     *     @Model(type=User::class, groups={"get:users"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Requet - Syntax Error"
     * )
     * @OA\Response(
     *     response=401,
     *     description="JWT Token not found | Expired JWT Token"
     * )
     * @OA\Response(
     *     response=409,
     *     description="User already exists with this email for this company."
     * )
     * @OA\Response(
     *     response=500,
     *     description="Internal Error"
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function addUserByCompany(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserRepository $userRepository, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        if (empty($user->getDateRegistration())) {
            $user->setDateRegistration(new \DateTime('now'));
        }

        $user->setCompany($this->getUser());

        if (!empty($userRepository->findOneBy(['email' => $user->getEmail(), 'company' => $user->getCompany()]))) {
            return $this->json(['code' => 409, 'message' => 'User already exists with this email for this company.'], 409);
        }

        $errors = $validator->validate($user);

        if(count($errors) > 0) {
            return $this->json(['code' => 400, 'errors' => $errors], 400);
        }

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
     *     response=204,
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

            return $this->json(['success' => true, 'msg' => 'Success, User deleted.'], 204);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * Update patch an user by a company
     *
     * @Route("/api/users/{id}", name="update_patch_user", methods={"PATCH"})
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of user to update",
     *     @OA\Schema(type="integer")
     * )
     * @OA\RequestBody(
     *     description="User data to update",
     *     required=true,
     *     @Model(type=User::class, groups={"write:users"})
     * )
     * @OA\Response(
     *     response=200,
     *     description="Success - Return User Updated.",
     *     @Model(type=User::class, groups={"get:users"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Requet - Syntax Error"
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
     * @OA\Response(
     *     response=409,
     *     description="User already exists with this email for this company."
     * )
     * @OA\Response(
     *     response=500,
     *     description="Internal Error"
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function updatePatchUserByCompany(Request $request, User $user, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserRepository $userRepository, ValidatorInterface $validator)
    {
        if ($user->getCompany() === $this->getUser()) {
            $userData = $serializer->deserialize($request->getContent(), User::class, 'json');
            if (!empty($userData->getFirstName())) {
                $user->setFirstName($userData->getFirstName());
            }

            if (!empty($userData->getLastName())) {
                $user->setLastName($userData->getLastName());
            }

            if (!empty($userData->getEmail())) {
                $user->setEmail($userData->getEmail());

                if (!empty($userRepository->findOneBy(['email' => $user->getEmail(), 'company' => $user->getCompany()])) && $userRepository->findOneBy(['email' => $user->getEmail(), 'company' => $user->getCompany()])->getId() !== $user->getId()) {
                    return $this->json(['code' => 409, 'message' => 'User already exists with this email for this company.'], 409);
                }
            }

            if (!empty($userData->getDateRegistration())) {
                $user->setDateRegistration($userData->getDateRegistration());
            }

            $errors = $validator->validate($user);

            if(count($errors) > 0) {
                return $this->json(['code' => 400, 'errors' => $errors], 400);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json($user, 200, [], ['groups' => 'get:users']);
        }  else {
            throw new AccessDeniedHttpException();
        }
    }
}
