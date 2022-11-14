<?php

namespace App\Controller;

use App\Entity\Calculatrice;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CalculatriceController extends AbstractController
{
    #[Route('api/calculatrice', name: 'app_calculatrice')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CalculatriceController.php',
        ]);
    }

    //PREMIERE PARTIE DE LA FONTION 2
    //Permet d'utilliser la calculatrice pour l'ajouter dans la bdd
    /**
     * Request = 
     *  poid : int
     *  taille : int
     *  age : int
     *  cat : seche | pdm
     */
    #[Route('api/calculatrice/post', name: 'app_calculatrice.post', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function setCalculatrice(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
            $calculatrice = new Calculatrice();
            $poid = $request->request->get('poid');
            $height = $request->request->get('height');
            $age = $request->request->get('age');
            $cat = $request->request->get('cat');
            $id = $this->getUser()->getId(); // => il ment de ouf ca marche
            $calculatrice->setAllCalculatrice($poid, $height, $age, $id, $cat);
            $calculatrice = $serializer->deserialize($calculatrice, Calculatrice::class, 'json');
            $entityManager->persist($calculatrice);
            $entityManager->flush();
            // => APPEL LE PROF JY ARRIVE PAS
        return new JsonResponse($calculatrice, 201);
    }


    #[Route('api/calculatrice/get', name: 'app_calculatrice.getAll', methods: ['GET'])]
    public function getAllCalculatrice(EntityManager $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $calculatrice = $entityManager->getRepository(Calculatrice::class)->findAll();
        $calculatrice = $serializer->serialize($calculatrice, 'json', ['groups']);
         return new JsonResponse($calculatrice, RESPONSE::HTTP_OK,['groups' => $calculatrice], true);
    }
}
