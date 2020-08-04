<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/post/comment/{id}", name="comment")
     */
    public function create(Post $post,Request $request ){
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setPost($post->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('show', ['id' => $post->getId()]);
        }


        return $this->render('post/comment.html.twig',[
            'form' =>  $form->createView(),
            'post' => $post
        ]);
    }
}
