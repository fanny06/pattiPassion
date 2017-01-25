<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PP\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PP\CoreBundle\Entity\Favoris;
use PP\CoreBundle\Entity\Recette;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ProfileController
 *
 * @author Fanny
 */
class ProfileController extends Controller{
    public function favorisAction(Request $request,$id){
        
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        
        $recette=$em->getRepository("PPCoreBundle:Recette")->find($id);
        
        $favory= new Favoris();
        $favory->setUser($user);
        $favory->setRecette($recette);
        
        $em->persist($favory);
        $em->flush();
        
        return $this->redirectToRoute('core_profil');
    }
}
