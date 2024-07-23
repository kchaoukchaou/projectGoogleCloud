<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\GoogleCloudStorageService;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap5View;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleCloudStorageController extends AbstractController
{
    private $gcsService;
    private $params;

    public function __construct(GoogleCloudStorageService $gcsService,ParameterBagInterface $params)
    {
        $this->gcsService = $gcsService;
        $this->params = $params;
    }


    #[Route('/list/images', name: 'list_images')]
    public function listImages(Request $request, GoogleCloudStorageService $gcsService): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $this->params->get('number_images_per_page'); // Number of images par page stocker dans services.yaml

        $images = $gcsService->getAllImages();
        $adapter = new ArrayAdapter($images);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($page);

        $routeGenerator = function($page) {
            return $this->generateUrl('list_images', ['page' => $page]);
        };

        $view = new TwitterBootstrap5View();
        $pagination = $view->render($pagerfanta, $routeGenerator);

        return $this->render('images/list.html.twig', [
            'images' => $pagerfanta->getCurrentPageResults(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/api/upload', name: 'api_images_upload', methods: ['POST'])]
    public function uploadApi(Request $request): Response
    {
        $images = $request->files->get('images');

        if (!$images) {
            return $this->json(['error' => 'No images provided'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($images as $image) {
            $destination = 'uploads/' .$image->getClientOriginalName();
            $filePath = $image->getPathname();
            try {
                $this->gcsService->uploadImages($filePath, $destination);

            } catch (FileException $e) {
                // Gestion des erreurs de fichier
                return new Response('Failed to upload Images: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            } catch (\Exception $e) {
                // Gestion des autres exceptions
                return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
       }

        return $this->json(['sucess' => 'upload images successfully'], Response::HTTP_OK);
    }
}