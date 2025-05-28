<?php

namespace App\Controller\Admin\Profile;

use App\Entity\User;
use App\Form\EditProfileFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/admin')]
final class ProfileController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/profile/index', name: 'app_admin_profile_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/profile/index.html.twig');
    }


    #[Route('/profile/edit-profile', name: 'app_admin_profile_edit_profile', methods:['GET', 'POST'])]
    public function editProfile(Request $request): Response
    {
        /**
         * @var User
         */
        $admin = $this->getUser();

        $form = $this->createForm(EditProfileFormType::class, $admin);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {

            $admin->setUpdatedAt(new DateTimeImmutable());

            $this->entityManager->persist($admin);
            $this->entityManager->flush();

            $this->addFlash("success", "Le profil a été modifié");

            return $this->redirectToRoute('app_admin_profile_index');
        }

        return $this->render('pages/admin/profile/edit_profile.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
