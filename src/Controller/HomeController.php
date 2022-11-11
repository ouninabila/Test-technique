<?php

namespace App\Controller;


use App\Service\HomeService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param HomeService $service
     * @param PaginatorInterface $paginator
     * @return Response $paginator
     */
    public function homePage(Request $request, HomeService $service, PaginatorInterface $paginator)
    {
        $images = $service->concatImages();
        $images = $paginator->paginate(
            $images,
            $request->query->getInt('page', 1),
            1
        );

        return $this->render('default/index.html.twig', ['images' => $images]);

    }

}