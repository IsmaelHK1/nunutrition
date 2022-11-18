<?php

namespace App\Controller;

use App\Entity\Picture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer as SerializerSerializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\serializer;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;

class PictureController extends AbstractController
{

    /** 
    * get a picture
    **/
    #[OA\Tag(name: 'Picture')]
    #[OA\Response( response: 200, description: 'Returns the picture', content: new OA\JsonContent( type: 'array', items: new OA\Items(ref: new Model(type: Picture::class, groups: ['getPicture']))))]
    #[Route('/api/picture/{idPicture}', name: 'app_picture.get', methods: ['GET'])]
    public function getPicture(SerializerInterface $serializer, EntityManagerInterface $entityManager, int $idPicture, UrlGeneratorInterface $urlGenerator, Request $request) : JsonResponse
    {
        //api call who gets the picture
        $picture = $entityManager->getRepository(Picture::class)->find($idPicture);
        $relativePath = $picture->getPublicPath() . "/" . $picture->getRealPath();
        $location = $request->getUriForPath($relativePath);
        $location = $location . str_replace("/assets", "assets", $relativePath);

       if ($picture){
           return new JsonResponse($serializer->serialize($picture, 'json', ['groups' => 'getPicture']), JsonResponse::HTTP_OK, ['Location' => $location], true);
       }else{
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
       }
    }

    /** 
    * create a picture
    **/
    #[OA\Tag(name: 'Picture')]
    #[OA\Parameter(name : "file", in : "query", schema : new OA\Schema(type : Picture::class), description : "file")]
    #[Route('api/picture', name: 'picture.create', methods: ['POST'])]
    public function createPicture(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $picture = new Picture();
        $files = $request->files->get('file');
        $picture->setFile($files);
        $picture->setMimeType($files->getMimeType());
        $picture->setRealName($files->getClientOriginalName());
        $picture->setPublicPath('/assets/pictures');
        $picture->setStatus('on');
        $entityManager->persist($picture);
        $entityManager->flush();

        $location = $this->generateUrl('picture.get', ['id' => $picture->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $jsonPictures = $serializer->serialize($picture, 'json', ['groups' => 'getPicture']);
        return new JsonResponse ($jsonPictures, Response::HTTP_CREATED, ['Location' => $location], true);
    }
}
