<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Text;

class TextController extends Controller
{
    /**
     * @Route("/", name="text_index")
     */
    public function indexAction()
    {
        $texts = $this->getDoctrine()->getRepository('AppBundle:Text')->findAll();
        
        return $this->render('AppBundle:Text:list.html.twig', array('texts' => $texts));
    }
    
    /**
     * @Route("/text/{id}", requirements={"id" = "\d+"}, name="text_show")
     */
    public function showAction($id)
    {
        $text = $this->getDoctrine()->getRepository('AppBundle:Text')->find($id);
        
        if (!$text)
        {
            throw $this->createNotFoundException("Texte $id inconnu.");
        }
        
        return $this->redirect($this->generateUrl('text_show_slug', array('slug' => $text->getSlug())));
        
    }
    
    /**
     * @Route("/text/{slug}", requirements={"slug"}, name="text_show_slug")
     */
    public function showBySlugAction($slug)
    {
        $text = $this->getDoctrine()->getRepository('AppBundle:Text')->findOneBySlug($slug);
        
        if (!$text)
        {
            throw $this->createNotFoundException("Texte $id inconnu.");
        }
        
        return $this->render('AppBundle:Text:show.html.twig', array('text' => $text));
        
    }
    
    /**
     * @Route("/text/new", name="text_create")
     */
    public function createAction(Request $request)
    {
        $text = new Text();
        
        $form = $this->createFormBuilder($text)
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('save', 'submit')
            ->getForm();
            
         $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getEntityManager();
            
            $text->setCreated(new \DateTime());
            
            $em->persist($text);
            $em->flush();
            
            dump($text);
            
            return $this->redirect($this->generateUrl('text_show', array('id' => $text->getId())));
        }
        
        return $this->render('AppBundle:Text:create.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("/text/{id}/edit", requirements={"id" = "\d+"}, name="text_edit")
     */
    public function editAction(Request $request, $id)
    {
        $text = $this->getDoctrine()->getRepository('AppBundle:Text')->find($id);
        
        if (!$text)
        {
            throw $this->createNotFoundException("Texte $id inconnu.");
        }
        
        $form = $this->createFormBuilder($text)
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('save', 'submit')
            ->getForm();
            
         $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getEntityManager();
            
            $text->setUpdated(new \DateTime());
            
            $em->persist($text);
            $em->flush();
            
            dump($text);
            
            return $this->redirect($this->generateUrl('text_show', array('id' => $text->getId())));
        }
        
        return $this->render('AppBundle:Text:create.html.twig', array('form' => $form->createView()));
    }
    
}
