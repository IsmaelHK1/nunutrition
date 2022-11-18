<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FruitSec;
use App\Entity\SucreLent;
use App\Entity\FruitLegume;
use App\Entity\Calculatrice;
use App\Entity\Legumineuse; 
use App\Entity\ProteineAnimal;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use OpenApi\Attributes as OA;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class UserExperienceController extends AbstractController
{
    //FONCTION N°1
    //donne un plat en fonction du nombre de cal qu'il a besoin d'avoir
    // l'api recupere l'id de l'utilisateur et le nombre de cal qu'il a besoin d'avoir pour lui proposer un repas
    #[Route('api/user/experience/get', name: 'app_user_experience.get', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserExperience(EntityManagerInterface $entityManager, SerializerInterface $serializer, TagAwareAdapterInterface $cache): JsonResponse
    {

        $idCache = 'getDish';
        $data = $cache->get($idCache, function (ItemInterface $item) use ($serializer,$entityManager) {
            $user = $this->getUser();
            $userId = $user->getId();
            if(is_null($userId)){
                return new JsonResponse($serializer->serialize([
                    "Error" => "Vous n'avez pas d'id",
                    "content" => $userId
                ], 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
            $calculatrice = $entityManager->getRepository(Calculatrice::class)->findOneByID($userId);
            $total_cal = $calculatrice->getTotalCal();
        
            $fruitLegume = $entityManager->getRepository(FruitLegume::class)->findAll();
            $fruitLegume = $serializer->serialize($fruitLegume, 'json', ['groups']);
            $fruitLegume = json_decode($fruitLegume, true);
            $fruitLegumeLength = count($fruitLegume);
        
            $fruitSec = $entityManager->getRepository(FruitSec::class)->findAll();
            $fruitSec = $serializer->serialize($fruitSec, 'json', ['groups']);
            $fruitSec = json_decode($fruitSec, true);
            $fruitSecLength = count($fruitSec);
        
            $legumineuse = $entityManager->getRepository(Legumineuse::class)->findAll();
            $legumineuse = $serializer->serialize($legumineuse, 'json', ['groups']);
            $legumineuse = json_decode($legumineuse, true);
            $legumineuseLength = count($legumineuse);
        
            $proteineAnimal = $entityManager->getRepository(ProteineAnimal::class)->findAll();
            $proteineAnimal = $serializer->serialize($proteineAnimal, 'json', ['groups']);
            $proteineAnimal = json_decode($proteineAnimal, true);
            $proteineAnimalLength = count($proteineAnimal);
        
            $sucreLent = $entityManager->getRepository(SucreLent::class)->findAll();
            $sucreLent = $serializer->serialize($sucreLent, 'json', ['groups']);
            $sucreLent = json_decode($sucreLent, true);
            $sucreLentLength = count($sucreLent);
        
            $dishCal = 0;
            $dish = [];
            do {
                $randFruitLegume = rand(0 , $fruitLegumeLength - 1);
                $randFruitSec = rand(0 , $fruitSecLength - 1);
                $randLegumineuse = rand(0 , $legumineuseLength - 1);
                $randProteineAnimal = rand(0 , $proteineAnimalLength - 1);
                $randSucreLent = rand(0 , $sucreLentLength - 1);
                $dishCal = $fruitLegume[$randFruitLegume]['Calories'] + $fruitSec[$randFruitSec]['Calories'] + $legumineuse[$randLegumineuse]['Calories'] + $proteineAnimal[$randProteineAnimal]['Calories'] + $sucreLent[$randSucreLent]['Calories'];
            } while ( $dishCal <= $total_cal/3 + 100 && $dishCal >= $total_cal/3 - 100);
            //divise par 3 le total car il y'a 3 repas par jour et je recupere environ 100 cal en plus ou en moins pour diversifier les repas
            $dish = [
                'fruitLegume' => $fruitLegume[$randFruitLegume],
                'fruitSec' => $fruitSec[$randFruitSec],
                'legumineuse' => $legumineuse[$randLegumineuse],
                'proteineAnimal' => $proteineAnimal[$randProteineAnimal],
                'sucreLent' => $sucreLent[$randSucreLent],
                'dishCal' => $dishCal
            ];
            return $dish;
        });
        
        return new JsonResponse($data, RESPONSE::HTTP_OK);
    }

    //FONCTION N°2
    //Donne un plat en fonction de la categorie de l'utilisateur
    //La categorie de l'utilisateur et son total calorique est écrit dans la table calculatrice grace a l'id de l'utilisateur
    //Toute la graille posséde un categorie (=$cat)
    //Il y'a un max de calories pour chaque repas : le total de calories de l'utilisateur divisé par 3

        /**
     * @Route("/api/fruitLegume", name="app_fruit_and_legume.getAll", methods={"GET"})
     * get the category of the user and return a daily dish appropriate to the his category
     */
    #[OA\Tag(name: 'Fonction 2 => UserExperience')]
    #[IsGranted('ROLE_USER')]
    #[Route('api/user/experience/filter', name: 'app_user_experience.filter', methods: ['GET'])]
    public function filterCat(EntityManagerInterface $entityManager, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse{
        $idCache = 'getDish';
        $data = $cache->get($idCache, function (ItemInterface $item) use ($serializer,$entityManager) {
            $userId = $this->getUser()->getId();
            if(is_null($userId)){
                return new JsonResponse($serializer->serialize([
                    "Error" => "Vous n'avez pas d'id",
                    "content" => $userId
                ], 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
            $calculatrice = $entityManager->getRepository(Calculatrice::class)->findOneByID($userId);
            $cat = $calculatrice->getCat();
            $total_cal = $calculatrice->getTotalCal();
    
            $fruitLegume = $entityManager->getRepository(FruitLegume::class)->findByCat($cat);
            $fruitLegume = $serializer->serialize($fruitLegume, 'json', ['groups']);
            $fruitLegume = json_decode($fruitLegume, true);
    
            $fruitSec = $entityManager->getRepository(FruitSec::class)->findByCat($cat);
            $fruitSec = $serializer->serialize($fruitSec, 'json', ['groups']);
            $fruitSec = json_decode($fruitSec, true);
    
            $legumineuse = $entityManager->getRepository(Legumineuse::class)->findByCat($cat);
            $legumineuse = $serializer->serialize($legumineuse, 'json', ['groups']);
            $legumineuse = json_decode($legumineuse, true);
    
            $proteineAnimal = $entityManager->getRepository(ProteineAnimal::class)->findByCat($cat);
            $proteineAnimal = $serializer->serialize($proteineAnimal, 'json', ['groups']);
            $proteineAnimal = json_decode($proteineAnimal, true);
    
            $sucreLent = $entityManager->getRepository(SucreLent::class)->findByCat($cat);
            $sucreLent = $serializer->serialize($sucreLent, 'json', ['groups']);
            $sucreLent = json_decode($sucreLent, true);
    
            $fruitLegumeLength = count($fruitLegume);
            $fruitSecLength = count($fruitSec);
            $legumineuseLength = count($legumineuse);
            $proteineAnimalLength = count($proteineAnimal);
            $sucreLentLength = count($sucreLent);
    
            $dishCal = 0;
            $dish = [];
    
        do {
            $randFruitLegume = rand(0 , $fruitLegumeLength - 1);
            $randFruitSec = rand(0 , $fruitSecLength - 1);
            $randLegumineuse = rand(0 , $legumineuseLength - 1);
            $randProteineAnimal = rand(0 , $proteineAnimalLength - 1);
            $randSucreLent = rand(0 , $sucreLentLength - 1);
            $dishCal = $fruitLegume[$randFruitLegume]['Calories'] + $fruitSec[$randFruitSec]['Calories'] + $legumineuse[$randLegumineuse]['Calories'] + $proteineAnimal[$randProteineAnimal]['Calories'] + $sucreLent[$randSucreLent]['Calories'];
        } while ( $dishCal <= $total_cal/3 + 100 && $dishCal >= $total_cal/3 - 100);
        //divise par 3 le total car il y'a 3 repas par jour et je recupere environ 100 cal en plus ou en moins pour diversifier les repas
        $dish = [
            'fruitLegume' => $fruitLegume[$randFruitLegume],
            'fruitSec' => $fruitSec[$randFruitSec],
            'legumineuse' => $legumineuse[$randLegumineuse],
            'proteineAnimal' => $proteineAnimal[$randProteineAnimal],
            'sucreLent' => $sucreLent[$randSucreLent],
            'dishCal' => $dishCal
        ];
        return $dish;
        });
        return new JsonResponse($data, RESPONSE::HTTP_OK);
    }

    /**
     * @Route("/api/user/experience/delete", name="app_user_experience.delete", methods={"DELETE"})
     * Delete a user
     */
    #[OA\Tag(name: 'UserExperience')]
    #[Route('api/user/experience/delete/{idUser}', name: 'app_user_experience.delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    #[ParamConverter('user', options: ['id' => 'idUser'], class:"App\Entity\User")]
    public function deleteUser(Request $request, EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
