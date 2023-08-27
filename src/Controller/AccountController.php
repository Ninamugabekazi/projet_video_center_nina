<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use App\Form\VideoType;
use App\Entity\Video;
use App\Repository\VideoRepository;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function show( VideoRepository $videoRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $videos = $paginator->paginate(
            $videoRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('account/show.html.twig', [
            'videos' => $videos,
            
        ]);
    }

    #[Route('/account/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()){
           
            $this->addFlash('error', 'You must login to edit your profile!');
            return $this->redirectToRoute('app_login');
    }
    $user = $this->getUser();
    $form = $this->createForm(UserFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Account successfuly updated !');
        return $this->redirectToRoute('app_account');
    }
      
        return $this->render('account/edit.html.twig', [
            'user' => $user,
            'userForm' => $form->createView()
        ]);
    }
}
