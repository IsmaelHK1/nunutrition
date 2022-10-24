<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface; 
use App\Entity\FruitLegume;
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class FruitAndLegumeController extends AbstractController
{

    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FruitAndLegumeController.php',
        ]);
    }

    /**
     * @Route("/api/fruitlegume", name="fruitlegume", methods={"GET"})
     */
    #[Route('/api/fruitNlegume', name: 'app_fruit_and_legume.getAll', methods: ['GET'])]
    public function getAllFruitNlegume(SerializerInterface $serializer, EntityManagerInterface $entityManager) : JsonResponse
    {
        //api call who gets all the fruit and legume
        $fruitNlegume = $entityManager->getRepository(FruitLegume::class)->findAll();
        //serialize the data
        $fruitNlegume = $serializer->serialize($fruitNlegume, 'json', ['groups']);
        //return the data
         return new JsonResponse($fruitNlegume, RESPONSE::HTTP_OK,['groups' => $fruitNlegume], true);
    }

    #[Route('/api/fruitNlegume/post', name: 'app_fruit_and_legume.insert', methods: ['POST'])]
    public function putFruitNLegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request) : JsonResponse
    {
        //get the data from the request
        $data = $request->getContent();
        //deserialize the data
        $fruitNlegume = $serializer->deserialize($data, FruitLegume::class, 'json');
        //validate the data
        $errors = $validator->validate($fruitNlegume);
        //if there is errors
        if ($errors->count() > 0) {
            //return the errors
            return new JsonResponse($errors, response::HTTP_BAD_REQUEST);
        }
        //insert the data in the database
        $entityManager->persist($fruitNlegume);
        $entityManager->flush();
        //return the data
        return new JsonResponse($fruitNlegume, 201);
    
    }
}