<?php

namespace App\Controller;

use App\Entity\FruitLegume;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FruitLegumeRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
// use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\SerializerInterface; 
use JMS\Serializer\Serializer;
use Hateoas\Configuration\Exclusion;
use JMS\Serializer\SerializationContext; 
use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FruitAndLegumeController extends AbstractController
{

    /**
     * @Route("/api/fruitLegume", name="fruitMLegume", methods={"GET"})
     */
    #[Route('/api/fruitLegume', name: 'app_fruit_and_legume.getAll', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getAllfruitLegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache, FruitLegumeRepository $repository) : JsonResponse
    {

        $idCache = 'getAllfruitLegume';
        $json = $cache->get($idCache, function (ItemInterface $item ) use ($repository, $serializer) {
            echo 'MISE EN CACHE';
            $item->tag("FruitlegumeCache");
            $fruitLegume = $repository->findAll();
            $context = SerializationContext::create()->setGroups(['getAllFruitLegume']);
            return $serializer->serialize($fruitLegume, 'json', $context);
        });
        return new JsonResponse($json, RESPONSE::HTTP_OK,[], true);
    }


 /**
     * @Route("/api/fruitLegume", name="fruitMLegume", methods={"GET"})
     */
    #[Route('/api/fruitLegume/{idFruitLegume}', name: 'app_fruit_and_legume.get', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getFruitLegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache, FruitLegumeRepository $repository, FruitLegume $fruitLegume) : JsonResponse

    {
        $idCache = 'getOneFruitLegume' . $fruitLegume->getId();
        $data = $cache->get($idCache, function (ItemInterface $item) use ($fruitLegume, $serializer) {
            echo 'MISE EN CACHE';
            $item->tag('FruitLegumeCache');
            $context = SerializationContext::create()->setGroups(['getFruitLegume']);
            return $serializer->serialize($fruitLegume, 'json', $context);
        });
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }





    #[Route('/api/fruitLegume/{idFruitLegume}', name: 'app_fruit_and_legume.insert', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    #[ParamConverter('fruitLegume', options: ['id' => 'idFruitLegume'])]
    public function putfruitLegume(SerializerInterface $serializer,TagAwareCacheInterface $cache, EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request, FruitLegume $fruitLegume) : JsonResponse
    {
        $updatedFruitLegume = $serializer->deserialize($request->getContent(), FruitLegume::class, 'json');
        $fruitLegume->setName($updatedFruitLegume->getName() ? $updatedFruitLegume->getName() : $fruitLegume->getName());
        $fruitLegume->setGlucides( $updatedFruitLegume->getGlucides() ? intval( $updatedFruitLegume->getGlucides()) : $fruitLegume->getGlucides());
        $fruitLegume->setProteines($updatedFruitLegume->getProteines() ? $updatedFruitLegume->getProteines() : $fruitLegume->getProteines());
        $fruitLegume->setLipides($updatedFruitLegume->getLipides() ? $updatedFruitLegume->getLipides() : $fruitLegume->getLipides());
        $fruitLegume->setCalories($updatedFruitLegume->getCalories() ? $updatedFruitLegume->getCalories() : $fruitLegume->getCalories());
        $fruitLegume->setCategories($updatedFruitLegume->getCategories() ? $updatedFruitLegume->getCategories() : $fruitLegume->getCategories());
        $fruitLegume->setStatus('on');

        $errors = $validator->validate($fruitLegume);
        if ($errors->count() > 0) {
            return new JsonResponse($errors, response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($fruitLegume);
        $entityManager->flush();

        $cache->invalidateTags(['FruitLegumeCache']);
        $context = SerializationContext::create()->setGroups(['getFruitLegume']);
        $data = $serializer->serialize($fruitLegume, 'json', $context);
        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    
    }

    #[Route('/api/fruitLegume', name: 'fruitLegume.create', methods: ['CREATE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createfruitLegume(ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $entityManager, Request $request, fruitLegumeRepository $fruitLegumeRepository, TagAwareCacheInterface $cache): JsonResponse
    {
        $cache->invalidateTags(['fruitLegumeCache']);
        $fruitLegume = $serializer->deserialize($request->getContent(), fruitLegume::class, 'json');
        $fruitLegume->setStatus("true");

        $content = $request->toArray();
        $idFruitLegume = $content['idFruitLegume'];
        $oneFruitLegume = $fruitLegumeRepository->find($idFruitLegume);

        $errors = $validator->validate($fruitLegume);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $fruitLegume->setfruitLegumeOwner($oneFruitLegume);

        $entityManager->persist($fruitLegume);
        $entityManager->flush();
        $context = SerializationContext::create()->setGroups(['getFruitLegume']);
        $jsonfruitLegume = $serializer->serialize($fruitLegume, 'json', $context);
        return new JsonResponse($jsonfruitLegume, Response::HTTP_CREATED, [], true);
    }


    #[Route('/api/fruitLegume/delete/{idFruitLegume}', name: 'app_fruit_and_legume.delete', methods: ['DELETE'])]
    #[ParamConverter('fruitLegume', options: ['id' => 'idFruitLegume'])]
    #[IsGranted('ROLE_USER')]
    public function deletefruitLegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache, FruitLegume $fruitLegume) : JsonResponse
    {

            $fruitLegume->setStatus("off");
            $cache->invalidateTags(['FruitlegumeCache']);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize("Delete done !", 'json'), Response::HTTP_OK, [], true);
    
    }
}