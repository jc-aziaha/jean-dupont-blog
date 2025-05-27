<?php

namespace App\Controller\Visitor\Contact;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\VisitorContactFormType;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    public function __construct(
        private SendEmailService $sendEmailService,
    ) {
    }

    #[Route('/contact', name: 'app_visitor_contact_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();

        $form = $this->createForm(VisitorContactFormType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTimeImmutable());

            /**
             * @var User
             */
            $user = $this->getUser();

            if (null != $user) {
                if ($user->getEmail() == $contact->getEmail()) {
                    $contact->setUser($user);
                }
            }

            $entityManager->persist($contact);
            $entityManager->flush();

            $this->sendEmailService->send([
                'sender_email' => 'medecine-du-monde@gmail.com',
                'sender_full_name' => 'Jean Dupont',
                'recipient_email' => 'medecine-du-monde@gmail.com',
                'subject' => 'Un message reçu sur votre blog',
                'html_template' => 'emails/contact.html.twig',
                'context' => [
                    'contact' => $contact,
                ],
            ]);

            $this->addFlash('success', 'Votre message a bien été envoyé. Je vous recontacterai dans les plus brefs délais.');

            return $this->redirectToRoute('app_visitor_contact_create');
        }

        return $this->render('pages/visitor/contact/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
