<?php

namespace App\Controller;

use App\Entity\FruitLegume;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FruitLegumeRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface; 
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    #[IsGranted('ROLE_USER')]
    public function getAllFruitNlegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache, FruitLegumeRepository $repository) : JsonResponse
    {

        $idCache = 'getAllFruitNlegume';
        $json = $cache->get($idCache, function (ItemInterface $item ) use ($repository, $serializer) {
            echo 'MISE EN CACHE';
            $item->tag("FuitNlegumeCache");
            $fruitNlegume = $repository->findAll();
            return $serializer->serialize($fruitNlegume, 'json');
        });
        return new JsonResponse($json, RESPONSE::HTTP_OK,[], true);
    }

    #[Route('/api/fruitNlegume/post', name: 'app_fruit_and_legume.insert', methods: ['POST'])]
    public function putFruitNLegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request) : JsonResponse
    {
        $data = $request->getContent();
        $fruitNlegume = $serializer->deserialize($data, FruitLegume::class, 'json');
        $errors = $validator->validate($fruitNlegume);
        if ($errors->count() > 0) {
            return new JsonResponse($errors, response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($fruitNlegume);
        $entityManager->flush();
        return new JsonResponse($fruitNlegume, 201);
    
    }

    #[IsGranted('ROLE_USER')]
    #[ParamConverter('FruitLegume', options: ['id' => 'idFruitLegume'], class:"App\Entity\FruitLegume")]
    #[Route('/api/fruitNlegume/delete/{idFruitLegume}', name: 'app_fruit_and_legume.delete', methods: ['DELETE'])]
    public function deleteFruitNlegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request) : JsonResponse
    {
        //delete methods
        $data = $request->getContent();
        $fruitNlegume = $serializer->deserialize($data, FruitLegume::class, 'json');
        $errors = $validator->validate($fruitNlegume);
        if ($errors->count() > 0) {
            return new JsonResponse($errors, response::HTTP_BAD_REQUEST);
        }
        $entityManager->remove($fruitNlegume);
        $entityManager->flush();
        return new JsonResponse($fruitNlegume, 204);
    
    }
}