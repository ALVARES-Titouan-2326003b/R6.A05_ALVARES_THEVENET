<?php

namespace App\DataFixtures;

use App\Entity\Qcm;
use App\Entity\Response;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QcmFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // QCM 1 : Programmation PHP
        $qcm1 = new Qcm();
        $qcm1->setTitle('QCM de Programmation PHP');
        $qcm1->setDescription('Un questionnaire pour tester vos connaissances en PHP et Symfony');
        $manager->persist($qcm1);

        // Réponses pour QCM 1
        $response1 = new Response();
        $response1->setQuestion('Quel est le framework PHP le plus utilisé ?');
        $response1->setResponse('Laravel, Symfony, CodeIgniter, Zend');
        $response1->setQcm($qcm1);
        $manager->persist($response1);

        $response2 = new Response();
        $response2->setQuestion('Qu\'est-ce que Doctrine ?');
        $response2->setResponse('Un ORM pour PHP');
        $response2->setQcm($qcm1);
        $manager->persist($response2);

        $response3 = new Response();
        $response3->setQuestion('Comment déclare-t-on une variable en PHP ?');
        $response3->setResponse('$variable = valeur;');
        $response3->setQcm($qcm1);
        $manager->persist($response3);

        // QCM 2 : Base de données
        $qcm2 = new Qcm();
        $qcm2->setTitle('QCM de Base de données');
        $qcm2->setDescription('Questions sur MySQL, PostgreSQL et les concepts de bases de données');
        $manager->persist($qcm2);

        // Réponses pour QCM 2
        $response4 = new Response();
        $response4->setQuestion('Qu\'est-ce qu\'une clé primaire ?');
        $response4->setResponse('Un identifiant unique pour chaque ligne d\'une table');
        $response4->setQcm($qcm2);
        $manager->persist($response4);

        $response5 = new Response();
        $response5->setQuestion('Quelle commande SQL permet de récupérer des données ?');
        $response5->setResponse('SELECT');
        $response5->setQcm($qcm2);
        $manager->persist($response5);

        $response6 = new Response();
        $response6->setQuestion('Qu\'est-ce qu\'une jointure (JOIN) ?');
        $response6->setResponse('Une opération qui combine des lignes de plusieurs tables');
        $response6->setQcm($qcm2);
        $manager->persist($response6);

        // QCM 3 : Architecture Web
        $qcm3 = new Qcm();
        $qcm3->setTitle('QCM d\'Architecture Web');
        $qcm3->setDescription('Testez vos connaissances sur les patterns MVC, REST et les architectures modernes');
        $manager->persist($qcm3);

        // Réponses pour QCM 3
        $response7 = new Response();
        $response7->setQuestion('Que signifie MVC ?');
        $response7->setResponse('Model-View-Controller');
        $response7->setQcm($qcm3);
        $manager->persist($response7);

        $response8 = new Response();
        $response8->setQuestion('Qu\'est-ce qu\'une API REST ?');
        $response8->setResponse('Une interface de programmation utilisant les méthodes HTTP');
        $response8->setQcm($qcm3);
        $manager->persist($response8);

        $response9 = new Response();
        $response9->setQuestion('Quels sont les verbes HTTP principaux ?');
        $response9->setResponse('GET, POST, PUT, PATCH, DELETE');
        $response9->setQcm($qcm3);
        $manager->persist($response9);

        // Enregistrer toutes les données en base
        $manager->flush();
    }
}
