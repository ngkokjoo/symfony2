<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Entity\book;

class BooksController extends Controller
{
    /**
     * @Route("/books/author", name="authorPage")
     */
    public function authorAction(Request $request)
    {
        //return new Response('Book store Application');
        return $this->render('books/author.html.twig',[]);

        // return $this->render('default/index.html.twig', [

        // ]);
    }

    /**
     * @Route("/books/display", name="displayPage")
     */
    public function displayAction(){
        $display=$this->getDoctrine()->getRepository('AppBundle:book')->findAll();

        return $this->render('books/display.html.twig',[
            'data'=>$display
        ]);
    }

    /**
     * @Route("/books/new", name="newPage")
     */
    public function newAction(Request $request){

        // create form layout using form builder
        $bk = new book();
        $form = $this->createFormBuilder($bk)
            ->add('name',TextType::class)
            ->add('author',TextType::class)
            ->add('price',TextType::class)
            ->add('createdTime',DateTimeType::class)
            ->add('save',SubmitType::class, ['label'=>'Submit'])
            ->getform();

        // handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $book = $form->getData();
            $em = $this->getDoctrine()->getManager();

            // tells doctrine to save the product
            $em->persist($book);
            // execute queries
            $em->flush();

            return $this->redirectToRoute('displayPage');
        }else{
            return $this->render('books/new.html.twig',[
                'form'=> $form->createView()
            ]);
        }
    }

    /**
     * @Route("/books/update/{id}", name="updatePage")
     */
    public function updateAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $bk = $em->getRepository('AppBundle:book')->find($id);

            if (!$bk){
                throw $this->createNotFoundException('No book found for id '.$id);
            }

        // display data in form layout using form builder
        $form = $this->createFormBuilder($bk)
            ->add('name',TextType::class)
            ->add('author',TextType::class)
            ->add('price',TextType::class)
            ->add('createdTime',DateTimeType::class)
            ->add('save',SubmitType::class, ['label'=>'Submit'])
            ->getform();

        // handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $book = $form->getData();
            $em = $this->getDoctrine()->getManager();

            // tells doctrine to save the product
            $em->persist($book);
            // execute queries
            $em->flush();

            return $this->redirectToRoute('displayPage');
        }else{
            return $this->render('books/new.html.twig',[
                'form'=> $form->createView()
            ]);
        }
    }

    /**
     * @Route("/books/delete/{id}", name="deletePage")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $bk = $em->getRepository('AppBundle:book')->find($id);

        if (!$bk){
            throw $this->createNotFoundException('No boo found for id '.$id);
        }

        $em->remove($bk);
        $em->flush();

        return $this->redirectToRoute('displayPage');
    }
}
