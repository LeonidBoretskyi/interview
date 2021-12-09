<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\UserAnswerQuestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 *
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @param Question $question
     * @param Answer $answer
     * @return JsonResponse
     *
     * @Route("/set-answer/{question}/{answer}", name="api_set_answer")
     */
    public function setAnswer(Question $question, Answer $answer) : JsonResponse
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        try{
            $uaq = new UserAnswerQuestion();
            $uaq->setQuestion($question);
            $uaq->setAnswer($answer);
            $uaq->setUser($user);
            $entityManager->persist($uaq);
            $entityManager->flush();
        }catch (\Exception $e){
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        return $this->json(['success' => 'true']);
    }
}