<?php
// src/Controller/ServiceController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_service_show')]
    public function showService(string $name): Response
    {
        // On passe la variable 'name' au template Twig
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/go-to-index', name: 'app_go_to_index')]
    public function goToIndex(): Response
    {
        // redirige vers la route nommée 'app_home' (HomeController::index)
        return $this->redirectToRoute('app_home');
    }
}
