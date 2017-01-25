<?php

namespace PP\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PP\CoreBundle\Entity\Contact;
use PP\CoreBundle\Entity\Recette;
use PP\CoreBundle\Entity\Comments;
use PP\CoreBundle\Entity\Astuce;
use PP\CoreBundle\Form\ImageType;
use PP\CoreBundle\Form\ImageUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException; 

/**
 * Description of ViewsController
 *
 * @author Fanny
 */
class ViewsController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $recettes = $em->getRepository('PPCoreBundle:Recette')->findBy([], ['id' => 'desc'], 3);
        $astuces = $em->getRepository('PPCoreBundle:Astuce')->findBy([], ['id' => 'desc'], 4);
        $allrecettes = $em->getRepository('PPCoreBundle:Recette')->findAll();
        
        return $this->render('PPCoreBundle:Views:index.html.twig', array(
                    'astuces' => $astuces,
                    'recettes' => $recettes,
                    'allrecettes' => $allrecettes
        ));
    }

    public function astucesAction($page) {
        $em = $this->getDoctrine()->getManager();
        $nbrePage=2;
        $astuces = $em->getRepository('PPCoreBundle:Astuce')->getPageAstuces($page, $nbrePage);
        $nbPage = ceil(count($astuces) / $nbrePage);
        return $this->render('PPCoreBundle:Views:astuces.html.twig', array(
            'nbPage' => $nbPage,
            'page' => $page,
            'astuces' => $astuces
        ));
    }
    
    public function astuceAction($id){
        $em = $this->getDoctrine()->getManager();
        $astuce = $em->getRepository('PPCoreBundle:Astuce')->find($id);
        
        return $this->render('PPCoreBundle:Views:astuce.html.twig', array(
            'astuce' => $astuce
        ));
    }
    
    public function astuceaddAction(Request $request) {
        $addAstuce = new Astuce();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $addAstuce);

        $formBuilder
                ->add('title', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Title'],
                ))
                ->add('text', TextareaType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Texte'],
                ))
                ->add('envoyer', SubmitType::class)
        ;
        $form = $formBuilder->getForm();
        
         if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($addAstuce);
                $em->flush();

                return $this->redirectToRoute('core_profil', array('id' => $addAstuce->getId()));
            }
        }
        
        return $this->render('PPCoreBundle:Views:addastuces.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function recettesAction($page) {
        $em = $this->getDoctrine()->getManager();
        $nbrePage = 3;
        $recettes = $em->getRepository('PPCoreBundle:Recette')->getPageRecettes($page, $nbrePage);
        $nbPage = ceil(count($recettes) / $nbrePage);
        return $this->render('PPCoreBundle:Views:recettes.html.twig', array(
            'recettes' => $recettes,
            'nbPage' => $nbPage,
            'page' => $page
        ));
    }

    public function recetteAction(Request $request, $id, $page) {
        $em = $this->getDoctrine()->getManager();
        $recette = $em->getRepository('PPCoreBundle:Recette')->find($id);

        $nbrePage = 2;
        $comments = $em->getRepository('PPCoreBundle:Comments')->getPageComments($page,$nbrePage);
        $nbPage = ceil(count($comments) / $nbrePage);
        
        $comment = new Comments();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $comment);

        $formBuilder
                ->add('comment', TextareaType::class)
                ->add('envoyer', SubmitType::class)
        ;
        
        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $comment->setRecette($recette);
                $comment->setUser($this->getUser());
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('core_recette', array('id' => $id));
            }
        } 
        //$comments = $em->getRepository('PPCoreBundle:Comments')->findByRecette($recette, ['id' => 'desc']);

        return $this->render('PPCoreBundle:Views:recette.html.twig', array(
                    'recette' => $recette,
                    'form' => $form->createView(),
                    'comments' => $comments,
                    'nbPage' => $nbPage,
                    'page' => $page
        ));
    }

    public function contactAction(Request $request) {
        $contact = new Contact();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $contact);

        $formBuilder
                ->add('name', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Nom'],
                ))
                ->add('firstname', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Prénom'],
                ))
                ->add('mail', EmailType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Email'],
                ))
                ->add('message', TextareaType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Message'],
                ))
                ->add('envoyer', SubmitType::class)
        ;
        $form = $formBuilder->getForm();
       
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                return $this->redirectToRoute('core_contact', array('id' => $contact->getId()));
            }
        }        
        return $this->render('PPCoreBundle:Views:contact.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function messagesAction() {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('PPCoreBundle:Contact')->findAll();
        
        return $this->render('PPCoreBundle:Views:messages.html.twig', array(
            'messages' => $messages 
        ));
    }

    public function manageuserAction(){
        $em = $this->getDoctrine()->getManager();
        $manageuser = $em->getRepository('PPUserBundle:User')->findAll();
        
        return $this->render('PPCoreBundle:Views:manageuser.html.twig', array(
            'manageusers' => $manageuser
        ));
    }
    
    public function profilAction() {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $favoris = $em->getRepository("PPCoreBundle:Favoris")->findByUser($user);

        return $this->render('PPCoreBundle:Views:profil.html.twig', array(
                    'favoris' => $favoris,
                    'user' => $user
        ));
    }

    public function addAction(Request $request) {
        $add = new Recette();
        $formAddBuilder = $this->get('form.factory')->createBuilder(FormType::class, $add);

        $formAddBuilder
                ->add('time', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Temps'],
                ))
                ->add('difficulty', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Difficulté'],
                ))
                ->add('ingredients', TextareaType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Ingrédients'],
                ))
                ->add('step', TextareaType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Etapes'],
                ))
                ->add('photo', ImageType::class)
                ->add('title', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Titre du dessert'],
                ))
                ->add('type', EntityType::class, array(
                    'class' => 'PPCoreBundle:Type',
                    'choice_label' => 'type'
                ))
                ->add('envoyer', SubmitType::class)
        ;
        $formAdd = $formAddBuilder->getForm();

        if ($request->isMethod('POST')) {
            $formAdd->handleRequest($request);

            if ($formAdd->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($add);
                $em->flush();

                return $this->redirectToRoute('core_profil', array('id' => $add->getId()));
            }
        }

        return $this->render('PPCoreBundle:Views:add.html.twig', array(
                    'formAdd' => $formAdd->createView(),
        ));
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $recette = $em->getRepository('PPCoreBundle:Recette')->find($id);
        $comments = $em->getRepository('PPCoreBundle:Comments')->findByRecette($recette);
        $favoris = $em->getRepository('PPCoreBundle:Favoris')->findByRecette($recette);

        foreach ($comments as $comment) {
            $em->remove($comment);
        }

        foreach ($favoris as $fav) {
            $em->remove($fav);
        }

        $em->remove($recette);
        $em->flush();

        return $this->redirectToRoute('core_recettes');
    }

    public function deleteFavAction($id) {
        
        $em = $this->getDoctrine()->getManager();

        $recette = $em->getRepository('PPCoreBundle:Recette')->find($id);
        $user = $this->getUser();
        
        $fav = $em->getRepository('PPCoreBundle:Favoris')->findOneBy(['user' => $user, 'recette' => $recette]);

        $em->remove($fav);
        $em->flush();
        return $this->redirectToRoute('core_profil');
    }
    
    public function deleteUserAction($id){
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('PPUserBundle:User')->find($id);
        
        $em->remove($user);
        $em->flush();
        
        return $this->redirectToRoute('core_manage_user');
    }

    public function deleteCommentAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $comment = $em->getRepository('PPCoreBundle:Comments')->find($request->get('comment'));
        
        $em->remove($comment);
        $em->flush();
        
        return new Response('cc');
    }
    
    public function deleteAstuceAction($id){
        $em = $this->getDoctrine()->getManager();
        
        $astuce = $em->getRepository('PPCoreBundle:Astuce')->find($id);
        
        $em->remove($astuce);
        $em->flush();
        
        return $this->redirectToRoute('core_astuces');
    }
    
    public function deleteMessageAction($id){
        $em = $this->getDoctrine()->getManager();
        
        $message = $em->getRepository('PPCoreBundle:Contact')->find($id);
        
        $em->remove($message);
        $em->flush();
        
        return $this->redirectToRoute('core_profil');
    }
    
    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $recette = $em->getRepository('PPCoreBundle:Recette')->find($id);
        
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $recette);

        $formBuilder
                ->add('time', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Temps'],
                ))
                ->add('difficulty', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Difficulté'],
                ))
                ->add('ingredients', TextareaType::class)
                ->add('step', TextareaType::class)
                ->add('photo', ImageType::class, array(
                    'required' => false
                ))
                ->add('title', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Titre du dessert'],
                ))
                ->add('type', EntityType::class, array(
                    'class' => 'PPCoreBundle:Type',
                    'choice_label' => 'type'
                ))
                ->add('envoyer', SubmitType::class)
        ;
        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($recette);
                $em->flush();

                return $this->redirectToRoute('core_recettes', array('id' => $recette->getId()));
            }
        }        
        
        return $this->render('PPCoreBundle:Views:edit.html.twig', array(
            'form' => $form -> createView()
        ));
    }
    
    public function editAstuceAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        
        $astuce = $em -> getRepository('PPCoreBundle:Astuce')->find($id);
        
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $astuce);

        $formBuilder
                ->add('title', TextType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Title'],
                ))
                ->add('text', TextareaType::class, array(
                    'label' => false, 'attr' => ['placeholder' => 'Texte'],
                ))
                ->add('envoyer', SubmitType::class)
        ;
        $form = $formBuilder->getForm();
        
         if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($astuce);
                $em->flush();

                return $this->redirectToRoute('core_astuces');
            }
        }
        
        return $this->render('PPCoreBundle:Views:editastuce.html.twig', array(
            'form' => $form -> createView()
        ));
        
    }
    
    public function resultatsAction($page) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('PPCoreBundle:Type')->findAll();
        if (isset($_GET['type'])){
            $type = $_GET['type']; 
        }else{
            $type = null;
        }
        $keyword = $_GET['keyword'];
        $paramRoute = array('keyword' => $keyword);
        $nbrePage = 1;
        $recettes = $em->getRepository('PPCoreBundle:Recette')->getSearchRecette($page,$nbrePage,$keyword,$type);
        $nbPage = ceil(count($recettes) / $nbrePage);

        return $this->render('PPCoreBundle:Views:resultats.html.twig',array(
            'recettes' => $recettes,
            'nbPage' => $nbPage,
            'page' => $page,
            'paramRoute' => $paramRoute,
            'types' => $types,
            'keyword' => $keyword
        ));
    }

    public function edituserAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('PPUserBundle:User')->find($id);
        
        if ($user != $this->getUser()){
            throw new AccessDeniedException('Vous n\'avez pas le droit de modifier cet utilisateur');
        }
        
        $formUserBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

        $formUserBuilder
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => false, 'attr' => ['placeholder' => 'Mot de passe']),
                    'second_options' => array('label' => false, 'attr' => ['placeholder' => 'Confirmez le mot de passe ']),
                    'invalid_message' => 'Le mot de passe ne correspond pas ',
                ))
                ->add('email', EmailType::class)
                ->add('photo', ImageUserType::class)
                ->add('envoyer', SubmitType::class)
        ;

        $formuser = $formUserBuilder->getform();

        if ($request->isMethod('POST')) {
            $formuser->handleRequest($request);

            if ($formuser->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('core_home', array('id' => $user->getId()));
            }
        }
        return $this->render('PPCoreBundle:Views:edituser.html.twig', array(
            'formuser' => $formuser -> createView()
        ));
        
    }
}
