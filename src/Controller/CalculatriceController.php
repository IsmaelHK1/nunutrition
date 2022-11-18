<?php

namespace App\Controller;

use App\Entity\Calculatrice;
use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Hateoas\Configuration\Exclusion;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface; 
use JMS\Serializer\SerializationContext; 
use App\Repository\CalculatriceRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;

class CalculatriceController extends AbstractController
{
    //PREMIERE PARTIE DE LA FONTION 2
    //Permet d'utilliser la calculatrice pour l'ajouter dans la bdd

    /**
     * Ajoute tout le calcul utiliser et la reponse a celui-ci pour évaluer le nombre de calorie a dépensé pour une personne en fonction de sa catégorie de régime et de son physique
     **/
    #[OA\Tag(name: 'Calculatrice')]
    #[OA\Parameter(name : "height", in : "query", schema : new OA\Schema(type : 'int'))]
    #[OA\Parameter(name : "poid", in : "query", schema : new OA\Schema(type :'int'))]
    #[OA\Parameter(name : "age", in : "query", schema : new OA\Schema(type :'int'))]
    #[OA\Parameter(name : "categorie : ['PDM', 'seche']", in : "query", schema : new OA\Schema(type :'string'))]
    #[Security(name: 'Bearer')]
    #[Route('api/calculatrice/post', name: 'app_calculatrice.create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function setCalculatrice(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager,CalculatriceRepository $calculatrice, TagAwareCacheInterface $cache,ValidatorInterface $validator, UserRepository $userRepo): JsonResponse
    {
        $oneCalcuatrice = $request->getContent();
        $oneCalcuatrice = $serializer->deserialize($oneCalcuatrice, Calculatrice::class, 'json');
        $height = $oneCalcuatrice->getHeight();
        $poid = $oneCalcuatrice->getPoid();
        $age = $oneCalcuatrice->getAge();
        $cat = $oneCalcuatrice->getCat();
        $id = $this->getUser()->getId(); // => il ment de ouf ca marche
        $user = $userRepo->find($id);
        $oneCalcuatrice->setStatus('on');
        if(is_null($id)){
            return new JsonResponse($serializer->serialize([
                "Error" => "Vous n'avez pas d'id",
                "content" => $id
            ], 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $oneCalcuatrice->setAllCalculatrice($poid, $height, $age, $user, $cat);
        $errors = $validator->validate($calculatrice);
        if ($errors->count()     > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $entityManager->persist($oneCalcuatrice);
        $entityManager->flush();
        $cache->invalidateTags(['CalculatriceCache']);
        $context = SerializationContext::create();
        $jsonCalculatrice = $serializer->serialize($oneCalcuatrice, 'json', $context);
    return new JsonResponse($jsonCalculatrice, Response::HTTP_CREATED, [], true);
    }

    #[OA\Tag(name: 'Calculatrice')]
    #[OA\Response( response: 200, description: 'Returns all the calcul and param used', content: new OA\JsonContent( type: 'array', items: new OA\Items(ref: new Model(type: Calculatrice::class, groups: ['getAllCalculatrice']))))]
    #[Route('api/calculatrice', name: 'app_calculatrice.getAll', methods: ['GET'])]
    public function getAllCalculatrice( SerializerInterface $serializer, TagAwareAdapter $cache, CalculatriceRepository $repository): JsonResponse
    {
        $idCache = 'getAllCalculatrice';
        $json = $cache->get($idCache, function (ItemInterface $item ) use ($repository, $serializer) {
            echo 'MISE EN CACHE';
            $item->tag("CalculatriceCache");
            $calculatrice = $repository->findAll();
            $context = SerializationContext::create()->setGroups(['getAllCalculatrice']);
            return $serializer->serialize($calculatrice, 'json', $context);
        });
        return new JsonResponse($json, RESPONSE::HTTP_OK,[], true);
    }


    /**
     * Route for change a specific calculatrice
     */
    #[OA\Tag(name: 'Calculatrice')]
    #[Route('/api/calculatrice/{idCalculatrice}', name: 'calculatrice.insert', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    #[ParamConverter('calculatrice', options: ['id' => 'idCalculatrice'])]
    public function putCalculatrice(SerializerInterface $serializer,TagAwareCacheInterface $cache, EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request, Calculatrice $calculatrice) : JsonResponse
    {
        $updatedCalculatrice = $serializer->deserialize($request->getContent(), Calculatrice ::class, 'json');
        $calculatrice->setHeight( $updatedCalculatrice->getHeight() ? intval( $updatedCalculatrice->getHeight()) : $calculatrice->getHeight());
        $calculatrice->setPoid($updatedCalculatrice->getPoid() ? $updatedCalculatrice->getPoid() : $calculatrice->getPoid());
        $calculatrice->setAge($updatedCalculatrice->getAge() ? $updatedCalculatrice->getAge() : $calculatrice->getAge());
        $calculatrice->setCat($updatedCalculatrice->getCat() ? $updatedCalculatrice->getCat() : $calculatrice->getCat());
        $calculatrice->setUserId($updatedCalculatrice->getUserId() ? $updatedCalculatrice->getUserId() : $calculatrice->getUserId());
        $calculatrice->setStatus('on');

        $errors = $validator->validate($calculatrice);
        if ($errors->count() > 0) {
            return new JsonResponse($errors, response::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($calculatrice);
        $entityManager->flush();

        $cache->invalidateTags(['CalculatriceCache']);
        $context = SerializationContext::create()->setGroups(['getCalculatrice']);
        $data = $serializer->serialize($calculatrice, 'json', $context);
        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    
    }

}
