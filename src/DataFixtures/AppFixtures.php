<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Difficulty;
use App\Entity\Hike;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->addDifficulty($manager);
        $this->addStatus($manager);
        $this->addCampus($manager);
        $this->addCity($manager);
        $this->addLocation($manager);
        $this->addUser($manager);
        $this->addHike($manager);
    }

    public function addStatus(ObjectManager $manager):void
    {
        $status = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'];

        foreach ($status as $el){
            $newStatus = new Status();
            $newStatus->setLabel($el);
            $manager->persist($newStatus);
        }
        $manager->flush();
    }

    public function addDifficulty(ObjectManager $manager):void
    {
        $difficulties = ['Facile', 'Intermédiaire', 'Expert'];

        foreach ($difficulties as $el){
            $newDifficulty = new Difficulty();
            $newDifficulty->setLabel($el);
            $manager->persist($newDifficulty);
        }
        $manager->flush();
    }

    public function addCampus(ObjectManager $manager):void
    {
        $campus = ['Chartres de Bretagne', 'Nantes', 'Niort', 'Quimper'];

        foreach ($campus as $el) {
            $newCampus = new Campus();
            $newCampus->setName($el);
            $manager->persist($newCampus);
        }
        $manager->flush();
    }

    public function addCity(ObjectManager $manager):void
    {
        $chartres = new City();
        $chartres->setName('Chartres de Bretagne');
        $chartres->setZipcode('35131');
        $manager->persist($chartres);

        $nantes = new City();
        $nantes->setName('Nantes');
        $nantes->setZipcode('44000');
        $manager->persist($nantes);

        $niort = new City();
        $niort->setName('Niort');
        $niort->setZipcode('79000');
        $manager->persist($niort);

        $quimper = new City();
        $quimper->setName('Quimper');
        $quimper->setZipcode('29000');
        $manager->persist($quimper);

        $manager->flush();
    }

    public function addUser(ObjectManager $manager):void
    {
        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'));
        $admin->setUsername('admin');
        $admin->setFirstname('Jean');
        $admin->setLastname('Admin');
        $admin->setPhoneNumber('0606060606');
        $admin->setEmail('admin@admin.fr');
        $admin->setActive(true);
        $admin->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $admin->setPicture('admin.jpg');
        $manager->persist($admin);

        $maud = new User();
        $maud->setRoles(['ROLE_USER']);
        $maud->setPassword($this->userPasswordHasher->hashPassword($maud, 'maud'));
        $maud->setUsername('maud');
        $maud->setFirstname('Maud');
        $maud->setLastname('Butin');
        $maud->setPhoneNumber('0666666666');
        $maud->setEmail('maud@maud.fr');
        $maud->setActive(true);
        $maud->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $maud->setPicture('maud.webp');
        $manager->persist($maud);

        $baptiste = new User();
        $baptiste->setRoles(['ROLE_USER']);
        $baptiste->setPassword($this->userPasswordHasher->hashPassword($baptiste, 'baptiste'));
        $baptiste->setUsername('baptiste');
        $baptiste->setFirstname('Baptiste');
        $baptiste->setLastname('Leblanc');
        $baptiste->setPhoneNumber('0607070707');
        $baptiste->setEmail('baptiste@baptiste.fr');
        $baptiste->setActive(true);
        $baptiste->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $baptiste->setPicture('baptiste.webp');
        $manager->persist($baptiste);

        $raman = new User();
        $raman->setRoles(['ROLE_USER']);
        $raman->setPassword($this->userPasswordHasher->hashPassword($raman, 'raman'));
        $raman->setUsername('raman');
        $raman->setFirstname('Raman');
        $raman->setLastname('Khaniakou');
        $raman->setPhoneNumber('0608080808');
        $raman->setEmail('raman@raman.fr');
        $raman->setActive(true);
        $raman->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $raman->setPicture('raman.png');
        $manager->persist($raman);

        $manager->flush();
    }

    public function addLocation(ObjectManager $manager):void
    {

        $location1 = new Location();
        $location1->setName('Le Moulin du Boël');
        $location1->setAddress('22 Le Boël, 35580 Guichen');
        $location1->setLatitude(47.9918449);
        $location1->setLongitude(-1.756662399999982);
        $location1->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location1);

        $location2 = new Location();
        $location2->setName('Parc des Gayeulles');
        $location2->setAddress('Rue du Professeur Maurice Audin, 35700 Rennes');
        $location2->setLatitude(48.1291);
        $location2->setLongitude(-1.6358);
        $location2->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location2);

        $location3 = new Location();
        $location3->setName('Vallée du Canut');
        $location3->setAddress('La Vallée du Canut, 35580 Lassy');
        $location3->setLatitude(47.9786);
        $location3->setLongitude(-1.8089);
        $location3->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location3);

        $location4 = new Location();
        $location4->setName('Lac de Grand-Lieu');
        $location4->setAddress('Bouaye, 44830');
        $location4->setLatitude(47.0482);
        $location4->setLongitude(-1.6945);
        $location4->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location4);

        $location5 = new Location();
        $location5->setName('Parc naturel de la Brière');
        $location5->setAddress('Kerhinet, 44410 Saint-Lyphard');
        $location5->setLatitude(47.3725);
        $location5->setLongitude(-2.3152);
        $location5->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location5);

        $location6 = new Location();
        $location6->setName('Sentier des Douaniers - Pointe du Raz');
        $location6->setAddress('Pointe du Raz, 29770 Plogoff');
        $location6->setLatitude(48.0390);
        $location6->setLongitude(-4.7435);
        $location6->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location6);

        $location7 = new Location();
        $location7->setName('Bois de Keradennec');
        $location7->setAddress('Rue de Keradennec, 29000 Quimper');
        $location7->setLatitude(47.9876);
        $location7->setLongitude(-4.0795);
        $location7->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location7);

        $location8 = new Location();
        $location8->setName('Forêt de Brocéliande - Paimpont');
        $location8->setAddress('35380 Paimpont');
        $location8->setLatitude(48.0182);
        $location8->setLongitude(-2.1713);
        $location8->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location8);

        $manager->flush();
    }

    public function addHike(ObjectManager $manager):void
    {
        $hike1 = new Hike();
        $hike1->setName('Randonnée puis crêpe au Moulin du Boël');
        $hike1->setDateEvent(new \DateTime('2026-04-04 08:00:00'));
        $hike1->setDateSubscription(new \DateTime('2026-04-04 11:00:00'));
        $hike1->setDuration(180);
        $hike1->setDescription("Randonnée au bord de la Vilaine et dans les hauteurs au dessus du Moulin du Boël. Prévoir des bonnes chaussures car le sentier est escarpé ! Nous finirons par une bonne crêpe au pied du Moulin s'il fait beau.");
        $hike1->setNbMaxSubscription(5);
        $hike1->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike1->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Intermédiaire']));
        $hike1->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike1->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Le Moulin du Boël']));
        $hike1->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike1->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike1->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike1->setPicture('moulin-boel.webp');
        $manager->persist($hike1);

        $hike2 = new Hike();
        $hike2->setName('Balade détente au parc des Gayeulles');
        $hike2->setDateSubscription(new \DateTime('2026-04-06 12:00:00'));
        $hike2->setDateEvent(new \DateTime('2026-04-08 14:00:00'));
        $hike2->setDuration(90);
        $hike2->setDescription("Petite balade accessible à tous dans le parc des Gayeulles. Idéal pour une sortie tranquille en famille ou entre amis.");
        $hike2->setNbMaxSubscription(3);
        $hike2->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike2->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Facile']));
        $hike2->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike2->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Parc des Gayeulles']));
        $hike2->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike2->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike2->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'raman']));
        $hike2->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike2->setPicture('parc-gayeules.jpg');
        $manager->persist($hike2);

        $hike3 = new Hike();
        $hike3->setName('Découverte de la vallée du Canut');
        $hike3->setDateSubscription(new \DateTime('2026-04-07 10:00:00'));
        $hike3->setDateEvent(new \DateTime('2026-04-12 09:30:00'));
        $hike3->setDuration(180);
        $hike3->setDescription("Randonnée dans la vallée du Canut avec passages boisés et chemins vallonnés. Quelques montées techniques, prévoir de bonnes chaussures.");
        $hike3->setNbMaxSubscription(8);
        $hike3->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike3->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Expert']));
        $hike3->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike3->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Vallée du Canut']));
        $hike3->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike3->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike3->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'raman']));
        $hike3->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike3->setPicture('canut.jpg');
        $manager->persist($hike3);

        $hike4 = new Hike();
        $hike4->setName('Tour du lac de Grand-Lieu');
        $hike4->setDateSubscription(new \DateTime('2026-04-08 08:00:00'));
        $hike4->setDateEvent(new \DateTime('2026-04-15 09:00:00'));
        $hike4->setDuration(210);
        $hike4->setDescription("Randonnée autour du lac de Grand-Lieu avec observation de la faune et de la flore. Parcours relativement plat mais assez long.");
        $hike4->setNbMaxSubscription(12);
        $hike4->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike4->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Intermédiaire']));
        $hike4->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike4->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Lac de Grand-Lieu']));
        $hike4->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'raman']));
        $hike4->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike4->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'raman']));
        $hike4->setPicture('lac-grand-lieu.jpg');
        $manager->persist($hike4);

        $hike5 = new Hike();
        $hike5->setName('Exploration du parc de la Brière');
        $hike5->setDateSubscription(new \DateTime('2026-03-20 11:00:00'));
        $hike5->setDateEvent(new \DateTime('2026-04-18 10:00:00'));
        $hike5->setDuration(150);
        $hike5->setDescription("Balade dans les marais de Brière entre chaumières et canaux. Terrain plat et facile, parfait pour une découverte nature.");
        $hike5->setNbMaxSubscription(10);
        $hike5->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Clôturée']));
        $hike5->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Facile']));
        $hike5->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike5->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Parc naturel de la Brière']));
        $hike5->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'raman']));
        $hike5->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike5->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'raman']));
        $hike5->setPicture('parc-briere.webp');
        $manager->persist($hike5);

        $hike6 = new Hike();
        $hike6->setName('Sentier côtier de la Pointe du Raz');
        $hike6->setDateSubscription(new \DateTime('2026-04-10 09:00:00'));
        $hike6->setDateEvent(new \DateTime('2026-04-20 10:30:00'));
        $hike6->setDuration(300);
        $hike6->setDescription("Randonnée spectaculaire sur le GR34 avec falaises et vue sur l’océan. Parcours exigeant avec vent et dénivelé.");
        $hike6->setNbMaxSubscription(6);
        $hike6->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike6->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Expert']));
        $hike6->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike6->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Sentier des Douaniers - Pointe du Raz']));
        $hike6->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike6->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike6->setPicture('pointe-raz.jpg');
        $manager->persist($hike6);

        $hike7 = new Hike();
        $hike7->setName('Balade nature au bois de Keradennec');
        $hike7->setDateSubscription(new \DateTime('2026-02-11 13:00:00'));
        $hike7->setDateEvent(new \DateTime('2026-03-23 15:00:00'));
        $hike7->setDuration(120);
        $hike7->setDescription("Petite randonnée urbaine dans un espace boisé agréable à Quimper. Parcours facile et accessible.");
        $hike7->setNbMaxSubscription(4);
        $hike7->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Passée']));
        $hike7->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Facile']));
        $hike7->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike7->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Bois de Keradennec']));
        $hike7->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike7->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike7->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike7->setPicture('keradennec.jpg');
        $manager->persist($hike7);

        $hike8 = new Hike();
        $hike8->setName('Immersion en forêt de Brocéliande');
        $hike8->setDateSubscription(new \DateTime('2026-04-05 07:00:00'));
        $hike8->setDateEvent(new \DateTime('2026-04-10 11:00:00'));
        $hike8->setDuration(240);
        $hike8->setDescription("Parcours mythique au cœur de Brocéliande entre étangs et forêt dense. Randonnée assez longue avec quelques dénivelés, idéale pour les amateurs de nature et de légendes.");
        $hike8->setNbMaxSubscription(10);
        $hike8->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Annulée']));
        $hike8->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'Intermédiaire']));
        $hike8->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike8->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande - Paimpont']));
        $hike8->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike8->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike8->setPicture('broceliande.jpg');
        $manager->persist($hike8);

        $manager->flush();
    }

}
