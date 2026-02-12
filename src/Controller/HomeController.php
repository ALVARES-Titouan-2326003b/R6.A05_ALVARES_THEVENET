<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\QcmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(QcmRepository $qcmRepository): Response
    {
        if (!$this->isGranted('IS_STUDENT')) {
            return $this->redirectToRoute('app_teacher_home');
        }

        // Récupérer les vidéos
        $videos = $qcmRepository->findBy(['type' => Type::VIDEO]);

        // Récupérer les documents PDF
        $documents = $qcmRepository->findBy(['type' => Type::PDF]);

        return $this->render('home/index.html.twig', [
            'videos' => $videos,
            'documents' => $documents,
        ]);
    }
}
