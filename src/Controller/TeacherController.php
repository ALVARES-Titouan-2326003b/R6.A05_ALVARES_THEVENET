<?php

namespace App\Controller;

use App\Entity\Qcm;
use App\Entity\Type;
use App\Repository\QcmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;


class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'app_teacher_home')]
    public function index(QcmRepository $qcmRepository): Response
    {
        // Récupérer les vidéos
        $videos = $qcmRepository->findBy(['type' => Type::VIDEO]);

        // Récupérer les documents PDF
        $documents = $qcmRepository->findBy(['type' => Type::PDF]);

        return $this->render('home/Teacher_index.html.twig', [
            'videos' => $videos,
            'documents' => $documents,
        ]);
    }

    #[Route('/teacher/upload/video', name: 'app_teacher_upload_video', methods: ['POST'])]
    public function uploadVideo(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $videoFile = $request->files->get('video_file');
        $title = $request->request->get('title');
        $description = $request->request->get('description');

        if ($videoFile) {
            $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$videoFile->guessExtension();

            try {
                $videoFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/videos',
                    $newFilename
                );

                $qcm = new Qcm();
                $qcm->setTitle($title);
                $qcm->setDescription($description);
                $qcm->setType(Type::VIDEO->value);
                $qcm->setPath('/uploads/videos/'.$newFilename);

                $entityManager->persist($qcm);
                $entityManager->flush();

                $this->addFlash('success', 'Vidéo uploadée avec succès !');
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de la vidéo');
            }
        }

        return $this->redirectToRoute('app_teacher_home');
    }

    #[Route('/teacher/upload/pdf', name: 'app_teacher_upload_pdf', methods: ['POST'])]
    public function uploadPdf(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $pdfFile = $request->files->get('pdf_file');
        $title = $request->request->get('title');
        $description = $request->request->get('description');

        if ($pdfFile) {
            $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pdfFile->guessExtension();

            try {
                $pdfFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/documents',
                    $newFilename
                );

                $qcm = new Qcm();
                $qcm->setTitle($title);
                $qcm->setDescription($description);
                $qcm->setType(Type::PDF->value);
                $qcm->setPath('/uploads/documents/'.$newFilename);

                $entityManager->persist($qcm);
                $entityManager->flush();

                $this->addFlash('success', 'Document PDF uploadé avec succès !');
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload du PDF');
            }
        }

        return $this->redirectToRoute('app_teacher_home');
    }
}
