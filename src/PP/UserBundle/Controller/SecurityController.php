<?php

namespace PP\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as Controller;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PP\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use PP\UserBundle\Form\ImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SecurityController extends Controller {

    public function loginAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('core_home');
        }
        $authenticationUtils = $this->get('security.authentication_utils');

        $user = new User();

        $formUserBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);


        $formUserBuilder
                ->add('username', TextType::class)
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => false, 'attr' => ['placeholder' => 'Mot de passe']),
                    'second_options' => array('label' => false, 'attr' => ['placeholder' => 'Confirmez le mot de passe ']),
                    'invalid_message' => 'Le mot de passe ne correspond pas ',
                ))
//                ->add('password',            RepeatedType::class)
                ->add('email', EmailType::class)
                ->add('photo', ImageType::class)
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

        return $this->render('PPUserBundle:Security:login.html.twig', array(
                    'username' => $authenticationUtils->getLastUsername(),
                    'error' => $authenticationUtils->getLastAuthenticationError(),
                    'formuser' => $formuser->createView(),
        ));
    }

}
