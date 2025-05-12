<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'admin_users')]
    public function dashboard(UserRepository $userRepository): Response
    {
        // Only allow the specific admin email to access
        if ($this->getUser()->getEmail() !== 'aymen@gmail.com') {
            throw new AccessDeniedException('You do not have access to this page.');
        }
        
        $users = $userRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
        ]);
    }
}
