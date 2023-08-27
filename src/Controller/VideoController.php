<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use App\Form\SearchType;
use App\Model\SearchData;



class VideoController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(VideoRepository $videoRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $pagination = $paginator->paginate(
            $videoRepository->paginationQuery($user),
            $request->query->get('page', 1),
            9
        );

        $search = false;

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $pagination = $paginator->paginate(
                $videoRepository->findBySearch($searchData),
                $request->query->get('page', 1),
                6
            );

            $search = true;

            // Obtenir le nombre total de vidéos trouvées lors de la recherche
            $totalVideos = $pagination->getTotalItemCount();

            // dd($totalVideos);
            return $this->render('video/index.html.twig', [

                'form' => $form,
                'pagination' => $pagination,
                'search' => $search,
                'searchData' => $searchData->q,
                'videos' => $videoRepository->findBySearch($searchData),
                'totalVideos' =>  $totalVideos
                

            ]);

        }

        return $this->render('video/index.html.twig', [
            'form' => $form->createView(),
            'videos' => $videoRepository->findAll(),
            'pagination' => $pagination,
            'search' => $search,
            'totalVideos' => $pagination->getTotalItemCount(),
        ]);
    }

    #[Route('/new', name: 'app_video_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false and video.isIsPremiumVideo() == true) {
                $this->addFlash('error', 'Vous devez confirmez votre email pour ajouter une vidéo!');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('error', 'Vous devez etre logué pour ajouter une vidéo!');
            return $this->redirectToRoute('app_login');
        }

        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();
            $this->addFlash('success','Votre vidéo a été ajouter avec succès !');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('video/new.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_video_show', methods: ['GET'])]
    public function show(Video $video): Response
    {
        return $this->render('video/show.html.twig', [
            'video' => $video,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_video_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Video $video, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('error', 'Vous devez confirmez votre email pour éditer une vidéo!');
                return $this->redirectToRoute('app_home');
            } else if ($video->getUser()->getEmail() !== $this->getUser()->getEmail()) {
                $this->addFlash('error', 'Vous devez être l\'utilisateur : ' . $video->getUser()->getFirstname() . ' pour éditer cette vidéo !');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('error', 'Vous devez etre logué pour éditer une vidéo!');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($video);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre video a été modifier avec succès !'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('video/edit.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_video_delete', methods: ['POST'])]
    public function delete(Request $request, Video $video, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $entityManager->remove($video);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre video a été supprimé avec succès !'
            );
        }

        return $this->redirectToRoute('app_home');
    }
}
