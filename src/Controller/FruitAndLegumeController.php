<?php

namespace App\Controller;

use App\Entity\FruitLegume;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Repository\FruitLegumeRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext; 
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use OpenApi\Attributes as OA;

use function PHPSTORM_META\type;

class FruitAndLegumeController extends AbstractController
{

    /**
     * @Route("/api/fruitLegume", name="app_fruit_and_legume.getAll", methods={"GET"})
     * get all fruit and legume from database
     */
    #[Route('/api/fruitLegume', name: 'app_fruit_and_legume.getAll', methods: ['GET'])]
    #[OA\Tag(name: 'FruitLegume')]
    #[OA\Response( response: 200, description: 'Returns all the fruit and legume', content: new OA\JsonContent( type: 'array', items: new OA\Items(ref: new Model(type: FruitLegume::class, groups: ['getAllFruitLegume']))))]
    #[IsGranted('ROLE_USER')]
    public function getAllfruitLegume(SerializerInterface $serializer, TagAwareCacheInterface $cache, FruitLegumeRepository $repository) : JsonResponse
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
     * @Route("/api/fruitLegume/{idFruitLegume}", name="fruitLegume.get", methods={"GET"})
     * Route for get one fruit Or Legume by id 
     */
    #[Route('/api/fruitLegume/{idFruitLegume}', name: 'FruitLegume.get', methods: ['GET'])]
    #[OA\Tag(name: 'FruitLegume')]
    #[IsGranted('ROLE_USER')]
    public function getFruitLegume(SerializerInterface $serializer, TagAwareCacheInterface $cache, FruitLegumeRepository $fruitLegume, int $idFruitLegume) : JsonResponse

    {
        $idCache = 'getOneFruitLegume';
        $data = $cache->get($idCache, function (ItemInterface $item) use ($fruitLegume, $serializer, $idFruitLegume) {
            echo 'MISE EN CACHE';
            $item->tag('FruitLegumeCache');
            $fruitLegume = $fruitLegume->find($idFruitLegume);
            $fruitLegume->setStatus('on');
            $context = SerializationContext::create()->setGroups(['getFruitLegume']);
            return $serializer->serialize($fruitLegume, 'json', $context);
        });
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/fruitLegume/{idFruitLegume}", name="fruitLegume.insert", methods={"POST"})
     */
    #[Route('/api/fruitLegume/{idFruitLegume}', name: 'FruitLegume.insert', methods: ['POST'])]
    #[OA\Tag(name: 'FruitLegume')]
    #[IsGranted('ROLE_ADMIN')]
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

    /**
     * @Route("/api/fruitLegume/post", name="fruitLegume.create", methods={"CREATE"})
     */
    #[Route('/api/fruitLegume/post', name: 'fruitLegume.create', methods: ['POST'])]
    #[OA\Tag(name: 'FruitLegume')]
    #[IsGranted('ROLE_ADMIN')]
    public function createfruitLegume(ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $entityManager, Request $request, fruitLegumeRepository $fruitLegumeRepository, TagAwareCacheInterface $cache): JsonResponse
    {
        $cache->invalidateTags(['fruitLegumeCache']);
        $fruitLegume = $serializer->deserialize($request->getContent(), FruitLegume::class, 'json');
        $fruitLegume->setStatus("true");

        $data = $request->getContent();
        $fruitLegume = $serializer->deserialize($data, FruitLegume::class, 'json');
        $fruitLegume->setStatus("on");
        $errors = $validator->validate($fruitLegume);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $entityManager->persist($fruitLegume);
        $entityManager->flush();
        $context = SerializationContext::create()->setGroups(['getFruitLegume']);
        $jsonfruitLegume = $serializer->serialize($fruitLegume, 'json', $context);
        return new JsonResponse($jsonfruitLegume, Response::HTTP_CREATED, [], true);
    }


    /**
     * @Route("/api/fruitLegume/{idFruitLegume}", name="FruitLegume.delete", methods={"DELETE"})
     */
    #[Route('/api/fruitLegume/delete/{idFruitLegume}', name: 'FruitLegume.delete', methods: ['DELETE'])]
    #[ParamConverter('fruitLegume', options: ['id' => 'idFruitLegume'])]
    #[OA\Tag(name: 'FruitLegume')]
    #[IsGranted('ROLE_ADMIN')]
    public function deletefruitLegume(SerializerInterface $serializer, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache, FruitLegume $fruitLegume) : JsonResponse
    {
        $fruitLegume->setStatus("off");
        $cache->invalidateTags(['FruitlegumeCache']);
        $entityManager->flush();
        return new JsonResponse($serializer->serialize("Delete done !", 'json'), Response::HTTP_OK, [], true);
    }
}