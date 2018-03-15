<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use  Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  UserBundle\Entity\User;
use  UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Mapping as ORM;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    public function ajouterAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        

         $form->handleRequest($request);
        if($request->isMethod('POST') && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
       return $this->redirect($this->generateUrl('user_voir', array('id' => $user->getId(), )));
        }
    	return $this->render('UserBundle:Default:ajouter.html.twig',array('form' => $form->createView(), ));
    }
    
    public function afficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("UserBundle:User")->findAll();
        
        return $this->render('UserBundle:Default:afficher.html.twig',array('users' => $users, ));
    }

    public function voirAction(User $user){
       

        return $this->render('UserBundle:Default:voir.html.twig',array('user' => $user ,));
       
    }

public function editerAction(User $User_D)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class,$User_D);
        

        $request = $this->get('request_stack')->getCurrentRequest();
        if($request->isMethod('POST')){
        $form->handleRequest($request);
        if( $form->isValid()){
      $user = $form->getData();
      $em->persist($user);
      $em->flush();
       return $this->redirect($this->generateUrl('user_voir', array('id' => $user->getId(), )));
        }
        }
        return $this->render('UserBundle:Default:editer.html.twig',array('id' => $User_D->getId(),'form' => $form->createView(), ));
    }

public function rechercherAction(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository("UserBundle:User");
        $motcle = $request->get('motcle');

        /*$products = $repository->findBy(array('ville' => $motcle,'destination' => $motcle1,'typeUser'=> $motcle2));*/
        $users = $repository->search($motcle);
        return $this->render('UserBundle:Default:rechercher.html.twig',array('users' => $users, ));
    }

    }
    