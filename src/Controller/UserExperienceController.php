<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Calculatrice;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserExperienceController extends AbstractController
{
    #[Route('/user/experience', name: 'app_user_experience')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserExperienceController.php',
        ]);
    }

    #[Route('/user/experience/post', name: 'app_user_experience.post', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function getUserExperience(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $id = $this->security->getUser()->getId();
        $calculatrice = new Calculatrice();
        $data = $request->getContent();
        $fruitNlegume = $serializer->deserialize($data, FruitLegume::class, 'json');
        
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserExperienceController.php',
        ]);
    }

    #[Route('/user/experience/delete', name: 'app_user_experience.delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    #[ParamConverter('user', options: ['id' => 'idUser'], class:"App\Entity\User")]
    public function deleteUser(Request $request, EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserExperienceController.php',
        ]);
    }
}
