<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    public function __construct(
        private QuestionRepository $questionRepository)
    {
    }

    #[Route('/api/v1/question', name: 'question_list')]
    public function index(): Response
    {
        $data = $this->questionRepository->findAll();
        return $this->json($data);
    }

    #[Route('/api/v1/question/{id}', name: 'question_get')]
    public function get(int $id): Response
    {
        $data = $this->questionRepository->find($id);
        return $this->json($data);
    }
}
