<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class FruitAndLegumeController extends AbstractController
{

    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FruitAndLegumeController.php',
        ]);
    }
    #[Route('/api/fruitNlegume', name: 'app_fruit_and_legume.getAll', methods: ['GET'])]
    public function getAllFruitNlegume(SerializerInterface $serializer, EntityManager $entityManager)
    {
        //api call who gets all the fruit and legume
        $fruitNlegume = $entityManager->getRepository(FruitLegume::class)->findAll();
        //serialize the data
        $fruitNlegume = $serializer->serialize($fruitNlegume, 'json');
        //return the data
        return new JsonResponse($fruitNlegume, 200, [], true);
    }
}
