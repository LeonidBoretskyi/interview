<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @param QuestionRepository $questionRepository
     * @return Response
     *
     * @Route("/", name="admin_index")
     */
    public function adminIndex(QuestionRepository $questionRepository) : Response
    {
        return $this->render("admin/index.html.twig", [
            'questions' => $questionRepository->findAll()
        ]);
    }

    /**
     * @param QuestionRepository $questionRepository
     * @return Response
     *
     * @Route("/chart", name="admin_chart")
     */
    public function chart(QuestionRepository $questionRepository) : Response
    {
        $questions = $questionRepository->findAll();

        return $this->render("admin/chart.html.twig", [
            'questions' => $questions
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("question/new", name="admin_question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /**
             * for some reason without this code
             * answer dont have question id
             * lok into arrayCollection in question entity
             *
             * @TODO find issue
             */
            foreach ($question->getAnswers() as $answer){
                $answer->setQuestion($question);
            }
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Question $question
     * @return Response
     *
     * @Route("question/{id}/edit", name="admin_question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Question $question
     * @return Response
     *
     * @Route("question/{id}", name="admin_question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }
}