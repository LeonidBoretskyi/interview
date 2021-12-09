<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\UserAnswerQuestion;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route("/", name="index")
     */
    public function index(Request $request, PaginatorInterface $paginator) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userAnswers = $entityManager->getRepository(UserAnswerQuestion::class)->findBy(['user' => $this->getUser()]);
        $questionsQuery = $entityManager->getRepository(Question::class)->getQueryFindAll();
        $questions = $paginator->paginate(
            $questionsQuery,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('main/index.html.twig', [
            'questions' => $questions,
            'userAnswers' => $userAnswers
        ]);
    }
}