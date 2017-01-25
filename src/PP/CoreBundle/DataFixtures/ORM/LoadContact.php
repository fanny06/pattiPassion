<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OC\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PP\CoreBundle\Entity\Contact;

class LoadContact implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        $articles = [
            [
                'name' => 'Fanny',
                'firstname' => 'Tilkin',
                'mail' => 'fanny.tilkin....',
                'message' => 'blablablalbalblajerjgneuirgvn',
            ]
        ];
        
        foreach ($articles as $article){
            $contact = new Contact();
            $contact
                ->setName($article['name'])
                ->setFirstname($article['firstname'])
                ->setMail($article['mail'])
                ->setMessage($article['message'])
                ;            
            $manager->persist($contact);
        }
        
        $manager->flush();
    }
}
