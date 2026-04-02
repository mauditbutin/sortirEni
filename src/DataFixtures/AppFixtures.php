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

    public function addStatus(ObjectManager $manager): void
    {
        $status = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée', 'Archivée'];

        foreach ($status as $el) {
            $newStatus = new Status();
            $newStatus->setLabel($el);
            $manager->persist($newStatus);
        }
        $manager->flush();
    }

    public function addDifficulty(ObjectManager $manager): void
    {
        $difficulties = ['Facile', 'Intermédiaire', 'Expert'];

        foreach ($difficulties as $el) {
            $newDifficulty = new Difficulty();
            $newDifficulty->setLabel($el);
            $manager->persist($newDifficulty);
        }
        $manager->flush();
    }

    public function addCampus(ObjectManager $manager): void
    {
        $campus = ['Chartres de Bretagne', 'Nantes', 'Niort', 'Quimper'];

        foreach ($campus as $el) {
            $newCampus = new Campus();
            $newCampus->setName($el);
            $manager->persist($newCampus);
        }
        $manager->flush();
    }

    public function addCity(ObjectManager $manager): void
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

    public function addUser(ObjectManager $manager): void
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
        $raman->setPicture('raman.jpg');
        $manager->persist($raman);

        $arthur = new User();
        $arthur->setRoles(['ROLE_USER']);
        $arthur->setPassword($this->userPasswordHasher->hashPassword($arthur, 'arthur'));
        $arthur->setUsername('arthur');
        $arthur->setFirstname('arthur');
        $arthur->setLastname('rughoobur');
        $arthur->setPhoneNumber('0608080824');
        $arthur->setEmail('arthur@arthur.fr');
        $arthur->setActive(true);
        $arthur->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $arthur->setPicture('arthur.jpg');
        $manager->persist($arthur);


        $adrienl = new User();
        $adrienl->setRoles(['ROLE_USER']);
        $adrienl->setPassword($this->userPasswordHasher->hashPassword($adrienl, 'adrienl'));
        $adrienl->setUsername('adrienl');
        $adrienl->setFirstname('adrien');
        $adrienl->setLastname('le clech');
        $adrienl->setPhoneNumber('0608080823');
        $adrienl->setEmail('adrienl@adrienl.fr');
        $adrienl->setActive(true);
        $adrienl->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $adrienl->setPicture('adrienl.png');
        $manager->persist($adrienl);

        $mael = new User();
        $mael->setRoles(['ROLE_USER']);
        $mael->setPassword($this->userPasswordHasher->hashPassword($mael, 'mael'));
        $mael->setUsername('mael');
        $mael->setFirstname('mael');
        $mael->setLastname('seigneur');
        $mael->setPhoneNumber('0608080822');
        $mael->setEmail('mael@mael.fr');
        $mael->setActive(true);
        $mael->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $mael->setPicture('mael.jpeg');
        $manager->persist($mael);

        $lena = new User();
        $lena->setRoles(['ROLE_USER']);
        $lena->setPassword($this->userPasswordHasher->hashPassword($lena, 'lena'));
        $lena->setUsername('lena');
        $lena->setFirstname('lena');
        $lena->setLastname('morfoisse');
        $lena->setPhoneNumber('0608080821');
        $lena->setEmail('lena@lena.fr');
        $lena->setActive(true);
        $lena->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $lena->setPicture('lena.webp');
        $manager->persist($lena);

        $mathilde = new User();
        $mathilde->setRoles(['ROLE_USER']);
        $mathilde->setPassword($this->userPasswordHasher->hashPassword($mathilde, 'mathilde'));
        $mathilde->setUsername('mathilde');
        $mathilde->setFirstname('mathilde');
        $mathilde->setLastname('guedon');
        $mathilde->setPhoneNumber('0608080820');
        $mathilde->setEmail('mathilde@mathilde.fr');
        $mathilde->setActive(true);
        $mathilde->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $mathilde->setPicture('mathilde.avif');
        $manager->persist($mathilde);

        $almo = new User();
        $almo->setRoles(['ROLE_USER']);
        $almo->setPassword($this->userPasswordHasher->hashPassword($almo, 'almo'));
        $almo->setUsername('almo');
        $almo->setFirstname('almo');
        $almo->setLastname('kabbashi ali ahmed');
        $almo->setPhoneNumber('0608080819');
        $almo->setEmail('almo@almo.fr');
        $almo->setActive(true);
        $almo->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $almo->setPicture('almo.jpeg');
        $manager->persist($almo);

        $david = new User();
        $david->setRoles(['ROLE_USER']);
        $david->setPassword($this->userPasswordHasher->hashPassword($david, 'david'));
        $david->setUsername('david');
        $david->setFirstname('david');
        $david->setLastname('thebault');
        $david->setPhoneNumber('0608080818');
        $david->setEmail('david@david.fr');
        $david->setActive(true);
        $david->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $david->setPicture('david.webp');
        $manager->persist($david);

        $thomas = new User();
        $thomas->setRoles(['ROLE_USER']);
        $thomas->setPassword($this->userPasswordHasher->hashPassword($thomas, 'thomas'));
        $thomas->setUsername('thomas');
        $thomas->setFirstname('thomas');
        $thomas->setLastname('danger');
        $thomas->setPhoneNumber('0608080817');
        $thomas->setEmail('thomas@thomas.fr');
        $thomas->setActive(true);
        $thomas->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $thomas->setPicture('thomas.webp');
        $manager->persist($thomas);

        $clara = new User();
        $clara->setRoles(['ROLE_USER']);
        $clara->setPassword($this->userPasswordHasher->hashPassword($clara, 'clara'));
        $clara->setUsername('clara');
        $clara->setFirstname('clara');
        $clara->setLastname('huet');
        $clara->setPhoneNumber('0608080816');
        $clara->setEmail('clara@clara.fr');
        $clara->setActive(true);
        $clara->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $clara->setPicture('clara.avif');
        $manager->persist($clara);

        $adrienq = new User();
        $adrienq->setRoles(['ROLE_USER']);
        $adrienq->setPassword($this->userPasswordHasher->hashPassword($adrienq, 'adrienq'));
        $adrienq->setUsername('adrienq');
        $adrienq->setFirstname('adrienq');
        $adrienq->setLastname('quintard');
        $adrienq->setPhoneNumber('0608080815');
        $adrienq->setEmail('adrienq@adrienq.fr');
        $adrienq->setActive(true);
        $adrienq->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $adrienq->setPicture('adrienq.jpg');
        $manager->persist($adrienq);

        $emilia = new User();
        $emilia->setRoles(['ROLE_USER']);
        $emilia->setPassword($this->userPasswordHasher->hashPassword($emilia, 'emilia'));
        $emilia->setUsername('emilia');
        $emilia->setFirstname('emilia');
        $emilia->setLastname('resanovic');
        $emilia->setPhoneNumber('0608080815');
        $emilia->setEmail('emilia@emilia.fr');
        $emilia->setActive(true);
        $emilia->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $emilia->setPicture('emilia.jpg');
        $manager->persist($emilia);

        $vanina = new User();
        $vanina->setRoles(['ROLE_USER']);
        $vanina->setPassword($this->userPasswordHasher->hashPassword($vanina, 'vanina'));
        $vanina->setUsername('vanina');
        $vanina->setFirstname('vanina');
        $vanina->setLastname('martignoni');
        $vanina->setPhoneNumber('0608080814');
        $vanina->setEmail('vanina@vanina.fr');
        $vanina->setActive(true);
        $vanina->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $vanina->setPicture('vanina.jpg');
        $manager->persist($vanina);

        $silvia = new User();
        $silvia->setRoles(['ROLE_USER']);
        $silvia->setPassword($this->userPasswordHasher->hashPassword($silvia, 'silvia'));
        $silvia->setUsername('silvia');
        $silvia->setFirstname('silvia');
        $silvia->setLastname('bamas');
        $silvia->setPhoneNumber('0608080813');
        $silvia->setEmail('silvia@silvia.fr');
        $silvia->setActive(true);
        $silvia->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $silvia->setPicture('silvia.jpeg');
        $manager->persist($silvia);

        $antonin = new User();
        $antonin->setRoles(['ROLE_USER']);
        $antonin->setPassword($this->userPasswordHasher->hashPassword($antonin, 'antonin'));
        $antonin->setUsername('antonin');
        $antonin->setFirstname('antonin');
        $antonin->setLastname('martel');
        $antonin->setPhoneNumber('0608080812');
        $antonin->setEmail('antonin@antonin.fr');
        $antonin->setActive(true);
        $antonin->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $antonin->setPicture('antonin.jpeg');
        $manager->persist($antonin);

        $camille = new User();
        $camille->setRoles(['ROLE_USER']);
        $camille->setPassword($this->userPasswordHasher->hashPassword($camille, 'camille'));
        $camille->setUsername('camille');
        $camille->setFirstname('camille');
        $camille->setLastname('payen');
        $camille->setPhoneNumber('0608080811');
        $camille->setEmail('camille@camille.fr');
        $camille->setActive(true);
        $camille->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $camille->setPicture('camille.jpeg');
        $manager->persist($camille);

        $nicolas = new User();
        $nicolas->setRoles(['ROLE_USER']);
        $nicolas->setPassword($this->userPasswordHasher->hashPassword($nicolas, 'nicolas'));
        $nicolas->setUsername('nicolas');
        $nicolas->setFirstname('nicolas');
        $nicolas->setLastname('tolantin');
        $nicolas->setPhoneNumber('0608080810');
        $nicolas->setEmail('nicolas@nicolas.fr');
        $nicolas->setActive(true);
        $nicolas->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $nicolas->setPicture('nicolas.jpeg');
        $manager->persist($nicolas);

        $yasmine = new User();
        $yasmine->setRoles(['ROLE_USER']);
        $yasmine->setPassword($this->userPasswordHasher->hashPassword($yasmine, 'yasmine'));
        $yasmine->setUsername('yasmine');
        $yasmine->setFirstname('yasmine');
        $yasmine->setLastname('hailoul');
        $yasmine->setPhoneNumber('0608080809');
        $yasmine->setEmail('yasmine@yasmine.fr');
        $yasmine->setActive(true);
        $yasmine->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $yasmine->setPicture('yasmine.jpg');
        $manager->persist($yasmine);

        $manager->flush();
    }

    public function addLocation(ObjectManager $manager): void
    {

        $location1 = new Location();
        $location1->setName('Le Moulin du Boël');
        $location1->setAddress('22 Le Boël');
        $location1->setLatitude(47.9918449);
        $location1->setLongitude(-1.756662399999982);
        $location1->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location1);

        $location2 = new Location();
        $location2->setName('Parc des Gayeulles');
        $location2->setAddress('Rue du Professeur Maurice Audin');
        $location2->setLatitude(48.1291);
        $location2->setLongitude(-1.6358);
        $location2->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location2);

        $location3 = new Location();
        $location3->setName('Vallée du Canut');
        $location3->setAddress('La Vallée du Canut');
        $location3->setLatitude(47.9786);
        $location3->setLongitude(-1.8089);
        $location3->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location3);

        $location4 = new Location();
        $location4->setName('Lac de Grand-Lieu');
        $location4->setAddress('Bouaye');
        $location4->setLatitude(47.0482);
        $location4->setLongitude(-1.6945);
        $location4->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location4);

        $location5 = new Location();
        $location5->setName('Parc naturel de la Brière');
        $location5->setAddress('Kerhinet');
        $location5->setLatitude(47.3725);
        $location5->setLongitude(-2.3152);
        $location5->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location5);

        $location6 = new Location();
        $location6->setName('Sentier des Douaniers - Pointe du Raz');
        $location6->setAddress('Pointe du Raz');
        $location6->setLatitude(48.0390);
        $location6->setLongitude(-4.7435);
        $location6->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location6);

        $location7 = new Location();
        $location7->setName('Bois de Keradennec');
        $location7->setAddress('Rue de Keradennec');
        $location7->setLatitude(47.9876);
        $location7->setLongitude(-4.0795);
        $location7->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location7);

        $location8 = new Location();
        $location8->setName('Forêt de Brocéliande');
        $location8->setAddress('35380 Paimpont');
        $location8->setLatitude(48.0182);
        $location8->setLongitude(-2.1713);
        $location8->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location8);

        $location9 = new Location();
        $location9->setName('Sentier des Landes de Monteneuf');
        $location9->setAddress('56380 Monteneuf');
        $location9->setLatitude(47.8972);
        $location9->setLongitude(-2.2083);
        $location9->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location9);

        $location10 = new Location();
        $location10->setName('Forêt de Paimpont - Val sans Retour');
        $location10->setAddress('35380 Paimpont');
        $location10->setLatitude(48.0126);
        $location10->setLongitude(-2.2219);
        $location10->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location10);

        $location11 = new Location();
        $location11->setName('Forêt de Rennes');
        $location11->setAddress('35510 Liffré');
        $location11->setLatitude(48.2135);
        $location11->setLongitude(-1.5102);
        $location11->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location11);

        $location12 = new Location();
        $location12->setName('Vallée du Couesnon');
        $location12->setAddress('35460 Saint-Ouen-la-Rouërie');
        $location12->setLatitude(48.4572);
        $location12->setLongitude(-1.3775);
        $location12->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location12);

        $location13 = new Location();
        $location13->setName('Bois de Soeuvres');
        $location13->setAddress('35770 Vern-sur-Seiche');
        $location13->setLatitude(48.0465);
        $location13->setLongitude(-1.5972);
        $location13->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location13);

        $location14 = new Location();
        $location14->setName('Forêt de Fougères');
        $location14->setAddress('35300 Fougères');
        $location14->setLatitude(48.3836);
        $location14->setLongitude(-1.2045);
        $location14->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location14);

        $location15 = new Location();
        $location15->setName('Étang de Boulet');
        $location15->setAddress('35440 Feins');
        $location15->setLatitude(48.3342);
        $location15->setLongitude(-1.6483);
        $location15->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $manager->persist($location15);

        $location16 = new Location();
        $location16->setName('Pointe du Raz');
        $location16->setAddress('29770 Plogoff');
        $location16->setLatitude(48.0397);
        $location16->setLongitude(-4.7346);
        $location16->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location16);

        $location17 = new Location();
        $location17->setName('Presqu’île de Crozon - Cap de la Chèvre');
        $location17->setAddress('29160 Crozon');
        $location17->setLatitude(48.1590);
        $location17->setLongitude(-4.5533);
        $location17->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location17);

        $location18 = new Location();
        $location18->setName('Monts d’Arrée - Roc’h Trévezel');
        $location18->setAddress('29410 Plounéour-Ménez');
        $location18->setLatitude(48.4135);
        $location18->setLongitude(-3.8812);
        $location18->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location18);

        $location19 = new Location();
        $location19->setName('Forêt de Huelgoat');
        $location19->setAddress('29690 Huelgoat');
        $location19->setLatitude(48.3603);
        $location19->setLongitude(-3.7465);
        $location19->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location19);

        $location20 = new Location();
        $location20->setName('Pointe de Pen-Hir');
        $location20->setAddress('29160 Camaret-sur-Mer');
        $location20->setLatitude(48.2707);
        $location20->setLongitude(-4.6226);
        $location20->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location20);

        $location21 = new Location();
        $location21->setName('Aber Wrac’h');
        $location21->setAddress('29870 Landéda');
        $location21->setLatitude(48.5983);
        $location21->setLongitude(-4.5672);
        $location21->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location21);

        $location22 = new Location();
        $location22->setName('Baie des Trépassés');
        $location22->setAddress('29770 Cléden-Cap-Sizun');
        $location22->setLatitude(48.0435);
        $location22->setLongitude(-4.7090);
        $location22->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Quimper']));
        $manager->persist($location22);

        $location23 = new Location();
        $location23->setName('Lac de Grand-Lieu');
        $location23->setAddress('44830 Saint-Aignan-Grandlieu');
        $location23->setLatitude(47.0735);
        $location23->setLongitude(-1.6402);
        $location23->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location23);

        $location24 = new Location();
        $location24->setName('Parc naturel régional de Brière');
        $location24->setAddress('44720 Saint-Joachim');
        $location24->setLatitude(47.3732);
        $location24->setLongitude(-2.2135);
        $location24->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location24);

        $location25 = new Location();
        $location25->setName('Sentier du littoral Pornic');
        $location25->setAddress('44210 Pornic');
        $location25->setLatitude(47.1122);
        $location25->setLongitude(-2.1035);
        $location25->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location25);

        $location26 = new Location();
        $location26->setName('Marais de Goulaine');
        $location26->setAddress('44115 Haute-Goulaine');
        $location26->setLatitude(47.1985);
        $location26->setLongitude(-1.3975);
        $location26->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location26);

        $location27 = new Location();
        $location27->setName('Forêt du Gâvre');
        $location27->setAddress('44130 Le Gâvre');
        $location27->setLatitude(47.5248);
        $location27->setLongitude(-1.8285);
        $location27->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location27);

        $location28 = new Location();
        $location28->setName('Île de Noirmoutier - Bois de la Chaise');
        $location28->setAddress('85330 Noirmoutier-en-l\'Île');
        $location28->setLatitude(47.0065);
        $location28->setLongitude(-2.2513);
        $location28->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location28);

        $location29 = new Location();
        $location29->setName('Bords de l’Erdre');
        $location29->setAddress('44470 Carquefou');
        $location29->setLatitude(47.2960);
        $location29->setLongitude(-1.4925);
        $location29->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location29);

        $location30 = new Location();
        $location30->setName('Côte sauvage du Croisic');
        $location30->setAddress('44490 Le Croisic');
        $location30->setLatitude(47.2925);
        $location30->setLongitude(-2.5163);
        $location30->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Nantes']));
        $manager->persist($location30);


        $location31 = new Location();
        $location31->setName('Marais Poitevin - Coulon');
        $location31->setAddress('79510 Coulon');
        $location31->setLatitude(46.3235);
        $location31->setLongitude(-0.5842);
        $location31->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location31);

        $location32 = new Location();
        $location32->setName('Forêt de Chizé');
        $location32->setAddress('79360 Villiers-en-Bois');
        $location32->setLatitude(46.1505);
        $location32->setLongitude(-0.4120);
        $location32->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location32);

        $location33 = new Location();
        $location33->setName('Étang de la Breure');
        $location33->setAddress('79160 Coulonges-sur-l\'Autize');
        $location33->setLatitude(46.4872);
        $location33->setLongitude(-0.5845);
        $location33->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location33);

        $location34 = new Location();
        $location34->setName('Forêt de Mervent-Vouvant');
        $location34->setAddress('85200 Mervent');
        $location34->setLatitude(46.5215);
        $location34->setLongitude(-0.7552);
        $location34->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location34);

        $location35 = new Location();
        $location35->setName('Massif forestier d’Aulnay');
        $location35->setAddress('17470 Aulnay');
        $location35->setLatitude(46.0212);
        $location35->setLongitude(-0.3468);
        $location35->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location35);

        $location36 = new Location();
        $location36->setName('Vallée du Thouet');
        $location36->setAddress('79200 Parthenay');
        $location36->setLatitude(46.6485);
        $location36->setLongitude(-0.2485);
        $location36->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location36);

        $location37 = new Location();
        $location37->setName('Plaine de Niort');
        $location37->setAddress('79000 Niort');
        $location37->setLatitude(46.3237);
        $location37->setLongitude(-0.4588);
        $location37->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location37);

        $location38 = new Location();
        $location38->setName('Forêt de Benon');
        $location38->setAddress('17170 Benon');
        $location38->setLatitude(46.2045);
        $location38->setLongitude(-0.8152);
        $location38->setCity($manager->getRepository(City::class)->findOneBy(['name' => 'Niort']));
        $manager->persist($location38);

        $manager->flush();
    }

    public function addHike(ObjectManager $manager): void
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

        $hike9 = new Hike();
        $hike9->setName('Balade nature et observation des oiseaux');
        $hike9->setDateSubscription(new \DateTime('2026-03-20 08:00:00'));
        $hike9->setDateEvent(new \DateTime('2026-04-02 10:00:00'));
        $hike9->setDuration(90);
        $hike9->setDescription("Une promenade tranquille idéale pour découvrir la faune locale. Nous prendrons le temps d’observer les oiseaux et d’écouter les sons de la nature. Accessible à tous.");
        $hike9->setNbMaxSubscription(8);
        $hike9->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike9->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike9->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike9->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Pointe du Raz']));
        $hike9->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike9->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike9->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike9->setPicture('hike9.jpg');
        $manager->persist($hike9);

        $hike10 = new Hike();
        $hike10->setName('Trail côtier sportif');
        $hike10->setDateSubscription(new \DateTime('2026-03-18 09:00:00'));
        $hike10->setDateEvent(new \DateTime('2026-04-05 09:30:00'));
        $hike10->setDuration(180);
        $hike10->setDescription("Un parcours dynamique le long du littoral avec quelques passages techniques. Idéal pour les amateurs de course nature. Prévoir une bonne condition physique.");
        $hike10->setNbMaxSubscription(10);
        $hike10->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike10->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike10->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike10->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Côte sauvage du Croisic']));
        $hike10->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike10->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike10->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike10->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike10->setPicture('hike10.jpg');
        $manager->persist($hike10);

        $hike11 = new Hike();
        $hike11->setName('Cueillette de champignons en forêt');
        $hike11->setDateSubscription(new \DateTime('2026-03-15 07:30:00'));
        $hike11->setDateEvent(new \DateTime('2026-04-06 10:00:00'));
        $hike11->setDuration(120);
        $hike11->setDescription("Partez à la recherche de champignons comestibles en forêt. Une activité ludique et pédagogique pour apprendre à reconnaître les espèces. Idéal en petit groupe.");
        $hike11->setNbMaxSubscription(6);
        $hike11->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike11->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike11->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike11->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Mervent-Vouvant']));
        $hike11->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike11->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike11->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike11->setPicture('hike11.jpg');
        $manager->persist($hike11);

        $hike12 = new Hike();
        $hike12->setName('Balade méditative en forêt');
        $hike12->setDateSubscription(new \DateTime('2026-03-16 08:00:00'));
        $hike12->setDateEvent(new \DateTime('2026-04-07 09:30:00'));
        $hike12->setDuration(60);
        $hike12->setDescription("Une marche lente et silencieuse pour se reconnecter à soi-même. Le parcours favorise la détente et l’observation de l’environnement. Accessible à tous.");
        $hike12->setNbMaxSubscription(5);
        $hike12->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike12->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike12->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike12->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande']));
        $hike12->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike12->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike12->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike12->setPicture('hike12.jpg');
        $manager->persist($hike12);

        $hike13 = new Hike();
        $hike13->setName('Sortie photo nature');
        $hike13->setDateSubscription(new \DateTime('2026-03-17 09:00:00'));
        $hike13->setDateEvent(new \DateTime('2026-04-08 14:00:00'));
        $hike13->setDuration(150);
        $hike13->setDescription("Immortalisez les plus beaux paysages lors de cette randonnée. Conseils et pauses prévues pour prendre des photos. Activité idéale pour amateurs de photographie.");
        $hike13->setNbMaxSubscription(7);
        $hike13->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike13->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike13->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike13->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Monts d’Arrée - Roc’h Trévezel']));
        $hike13->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike13->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike13->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike13->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike13->setPicture('hike13.jpg');
        $manager->persist($hike13);

        $hike14 = new Hike();
        $hike14->setName('Randonnée sportive en forêt');
        $hike14->setDateSubscription(new \DateTime('2026-03-18 07:00:00'));
        $hike14->setDateEvent(new \DateTime('2026-04-09 08:30:00'));
        $hike14->setDuration(210);
        $hike14->setDescription("Un parcours exigeant avec du dénivelé. Parfait pour les amateurs de défis sportifs en pleine nature. Bonne condition physique requise.");
        $hike14->setNbMaxSubscription(9);
        $hike14->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Clôturée']));
        $hike14->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike14->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike14->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt du Gâvre']));
        $hike14->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike14->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike14->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienl']));
        $hike14->setPicture('hike14.jpg');
        $manager->persist($hike14);

        $hike15 = new Hike();
        $hike15->setName('Balade familiale au bord de l’eau');
        $hike15->setDateSubscription(new \DateTime('2026-03-19 10:00:00'));
        $hike15->setDateEvent(new \DateTime('2026-04-10 15:00:00'));
        $hike15->setDuration(75);
        $hike15->setDescription("Une sortie accessible aux petits comme aux grands. Le parcours longe une rivière et offre de beaux points de vue. Idéal pour une sortie en famille.");
        $hike15->setNbMaxSubscription(12);
        $hike15->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike15->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike15->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike15->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Marais Poitevin - Coulon']));
        $hike15->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike15->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike15->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike15->setPicture('hike15.jpg');
        $manager->persist($hike15);

        $hike16 = new Hike();
        $hike16->setName('Exploration des sentiers cachés');
        $hike16->setDateSubscription(new \DateTime('2026-03-20 08:30:00'));
        $hike16->setDateEvent(new \DateTime('2026-04-11 11:00:00'));
        $hike16->setDuration(135);
        $hike16->setDescription("Partez à la découverte de chemins peu fréquentés. L’itinéraire alterne entre sous-bois et clairières. Une aventure dépaysante garantie.");
        $hike16->setNbMaxSubscription(6);
        $hike16->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Activité en cours']));
        $hike16->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike16->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike16->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Vallée du Canut']));
        $hike16->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike16->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike16->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike16->setPicture('hike16.jpg');
        $manager->persist($hike16);

        $hike17 = new Hike();
        $hike17->setName('Observation des oiseaux marins');
        $hike17->setDateSubscription(new \DateTime('2026-03-21 07:00:00'));
        $hike17->setDateEvent(new \DateTime('2026-04-12 09:00:00'));
        $hike17->setDuration(90);
        $hike17->setDescription("Découvrez les oiseaux du littoral lors de cette sortie guidée. Jumelles recommandées pour profiter pleinement de l’expérience. Parcours accessible.");
        $hike17->setNbMaxSubscription(8);
        $hike17->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Passée']));
        $hike17->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike17->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike17->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Sentier des Douaniers - Pointe du Raz']));
        $hike17->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike17->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike17->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'vanina']));
        $hike17->setPicture('hike17.jpg');
        $manager->persist($hike17);

        $hike18 = new Hike();
        $hike18->setName('Randonnée entre marais et canaux');
        $hike18->setDateSubscription(new \DateTime('2026-03-22 09:00:00'));
        $hike18->setDateEvent(new \DateTime('2026-04-13 14:00:00'));
        $hike18->setDuration(150);
        $hike18->setDescription("Un parcours dépaysant au cœur des marais. Entre eau et végétation, cette randonnée offre un cadre unique. Idéal pour les amateurs de paysages naturels.");
        $hike18->setNbMaxSubscription(7);
        $hike18->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike18->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike18->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike18->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Parc naturel de la Brière']));
        $hike18->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike18->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike18->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'almo']));
        $hike18->setPicture('hike18.jpg');
        $manager->persist($hike18);

        $hike19 = new Hike();
        $hike19->setName('Marche bien-être en pleine nature');
        $hike19->setDateSubscription(new \DateTime('2026-03-23 08:00:00'));
        $hike19->setDateEvent(new \DateTime('2026-04-14 10:00:00'));
        $hike19->setDuration(60);
        $hike19->setDescription("Une marche douce pour se détendre et respirer. Le parcours est simple et agréable. Parfait pour relâcher la pression.");
        $hike19->setNbMaxSubscription(4);
        $hike19->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Annulée']));
        $hike19->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike19->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike19->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Plaine de Niort']));
        $hike19->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike19->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike19->setPicture('hike19.jpg');
        $manager->persist($hike19);

        $hike20 = new Hike();
        $hike20->setName('Randonnée découverte du patrimoine');
        $hike20->setDateSubscription(new \DateTime('2026-03-24 09:00:00'));
        $hike20->setDateEvent(new \DateTime('2026-04-15 13:00:00'));
        $hike20->setDuration(180);
        $hike20->setDescription("Partez à la découverte du patrimoine local. Entre nature et histoire, cette randonnée est riche en découvertes. Accessible avec un minimum de condition physique.");
        $hike20->setNbMaxSubscription(9);
        $hike20->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Archivée']));
        $hike20->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike20->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike20->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Le Moulin du Boël']));
        $hike20->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike20->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike20->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike20->setPicture('hike20.jpg');
        $manager->persist($hike20);

        $hike21 = new Hike();
        $hike21->setName('Trail forestier intense');
        $hike21->setDateSubscription(new \DateTime('2026-03-25 08:00:00'));
        $hike21->setDateEvent(new \DateTime('2026-04-16 09:00:00'));
        $hike21->setDuration(210);
        $hike21->setDescription("Un parcours sportif en pleine forêt avec du dénivelé. Idéal pour les amateurs de trail et de défis physiques. Sensations garanties.");
        $hike21->setNbMaxSubscription(10);
        $hike21->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike21->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike21->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike21->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt du Gâvre']));
        $hike21->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike21->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike21->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienq']));
        $hike21->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike21->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike21->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike21->setPicture('hike21.jpg');
        $manager->persist($hike21);

        $hike22 = new Hike();
        $hike22->setName('Balade botanique guidée');
        $hike22->setDateSubscription(new \DateTime('2026-03-26 09:00:00'));
        $hike22->setDateEvent(new \DateTime('2026-04-17 14:00:00'));
        $hike22->setDuration(120);
        $hike22->setDescription("Découvrez les plantes locales et leurs usages. Une randonnée pédagogique et enrichissante. Idéale pour les curieux de nature.");
        $hike22->setNbMaxSubscription(8);
        $hike22->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike22->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike22->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike22->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Bois de Keradennec']));
        $hike22->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike22->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike22->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike22->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike22->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'vanina']));
        $hike22->setPicture('hike22.jpg');
        $manager->persist($hike22);

        $hike23 = new Hike();
        $hike23->setName('Randonnée lever de soleil');
        $hike23->setDateSubscription(new \DateTime('2026-03-27 07:00:00'));
        $hike23->setDateEvent(new \DateTime('2026-04-18 06:30:00'));
        $hike23->setDuration(90);
        $hike23->setDescription("Une expérience unique pour admirer le lever du soleil. Ambiance calme et paysages magnifiques. Idéal pour bien commencer la journée.");
        $hike23->setNbMaxSubscription(6);
        $hike23->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike23->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike23->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike23->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Parc des Gayeulles']));
        $hike23->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike23->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike23->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike23->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike23->setPicture('hike23.jpg');
        $manager->persist($hike23);

        $hike24 = new Hike();
        $hike24->setName('Exploration des falaises');
        $hike24->setDateSubscription(new \DateTime('2026-03-28 08:00:00'));
        $hike24->setDateEvent(new \DateTime('2026-04-19 11:00:00'));
        $hike24->setDuration(180);
        $hike24->setDescription("Partez à la découverte des falaises et points de vue spectaculaires. Une randonnée immersive en bord de mer. Sensations et paysages garantis.");
        $hike24->setNbMaxSubscription(9);
        $hike24->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Clôturée']));
        $hike24->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike24->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike24->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Sentier des Douaniers - Pointe du Raz']));
        $hike24->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike24->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike24->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike24->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'vanina']));
        $hike24->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike24->setPicture('hike24.jpg');
        $manager->persist($hike24);

        $hike25 = new Hike();
        $hike25->setName('Balade détente autour du lac');
        $hike25->setDateSubscription(new \DateTime('2026-03-29 10:00:00'));
        $hike25->setDateEvent(new \DateTime('2026-04-20 15:00:00'));
        $hike25->setDuration(75);
        $hike25->setDescription("Une promenade relaxante autour du lac. Parfaite pour profiter du calme et des paysages. Accessible à tous.");
        $hike25->setNbMaxSubscription(12);
        $hike25->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike25->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike25->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike25->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Lac de Grand-Lieu']));
        $hike25->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike25->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike25->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike25->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike25->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike25->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike25->setPicture('hike25.jpg');
        $manager->persist($hike25);

        $hike26 = new Hike();
        $hike26->setName('Randonnée entre forêt et rivière');
        $hike26->setDateSubscription(new \DateTime('2026-03-30 08:00:00'));
        $hike26->setDateEvent(new \DateTime('2026-04-21 10:00:00'));
        $hike26->setDuration(150);
        $hike26->setDescription("Un itinéraire varié entre bois et cours d’eau. Paysages changeants et ambiance apaisante. Idéal pour une sortie nature.");
        $hike26->setNbMaxSubscription(8);
        $hike26->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Activité en cours']));
        $hike26->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike26->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike26->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Le Moulin du Boël']));
        $hike26->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'adrienl']));
        $hike26->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienl']));
        $hike26->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike26->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike26->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike26->setPicture('hike26.jpg');
        $manager->persist($hike26);

        $hike27 = new Hike();
        $hike27->setName('Marche bien-être en marais');
        $hike27->setDateSubscription(new \DateTime('2026-03-31 09:00:00'));
        $hike27->setDateEvent(new \DateTime('2026-04-22 11:00:00'));
        $hike27->setDuration(90);
        $hike27->setDescription("Une marche douce au cœur des marais. Idéale pour se ressourcer et profiter du calme. Parcours accessible.");
        $hike27->setNbMaxSubscription(7);
        $hike27->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Passée']));
        $hike27->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike27->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike27->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Marais Poitevin - Coulon']));
        $hike27->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike27->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike27->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'vanina']));
        $hike27->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike27->setPicture('hike27.jpg');
        $manager->persist($hike27);

        $hike28 = new Hike();
        $hike28->setName('Randonnée découverte des landes');
        $hike28->setDateSubscription(new \DateTime('2026-04-01 08:00:00'));
        $hike28->setDateEvent(new \DateTime('2026-04-23 10:00:00'));
        $hike28->setDuration(120);
        $hike28->setDescription("Explorez les paysages de landes typiques. Une immersion dans un environnement sauvage et préservé. Activité enrichissante.");
        $hike28->setNbMaxSubscription(9);
        $hike28->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike28->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike28->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike28->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Monts d’Arrée - Roc’h Trévezel']));
        $hike28->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike28->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike28->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike28->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike28->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike28->setPicture('hike28.jpg');
        $manager->persist($hike28);

        $hike29 = new Hike();
        $hike29->setName('Balade urbaine et nature');
        $hike29->setDateSubscription(new \DateTime('2026-04-02 09:00:00'));
        $hike29->setDateEvent(new \DateTime('2026-04-24 14:00:00'));
        $hike29->setDuration(60);
        $hike29->setDescription("Un parcours mêlant ville et espaces verts. Idéal pour une sortie courte et agréable. Accessible à tous.");
        $hike29->setNbMaxSubscription(6);
        $hike29->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Annulée']));
        $hike29->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike29->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike29->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Bords de l’Erdre']));
        $hike29->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike29->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike29->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike29->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike29->setPicture('hike29.jpg');
        $manager->persist($hike29);

        $hike30 = new Hike();
        $hike30->setName('Grande randonnée immersive');
        $hike30->setDateSubscription(new \DateTime('2026-04-03 07:00:00'));
        $hike30->setDateEvent(new \DateTime('2026-04-25 08:00:00'));
        $hike30->setDuration(300);
        $hike30->setDescription("Une longue randonnée pour les passionnés. Parcours varié avec de beaux panoramas. Une vraie immersion en pleine nature.");
        $hike30->setNbMaxSubscription(12);
        $hike30->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike30->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike30->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike30->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande']));
        $hike30->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike30->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike30->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike30->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike30->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienq']));
        $hike30->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike30->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike30->setPicture('hike30.jpg');
        $manager->persist($hike30);

        $hike31 = new Hike();
        $hike31->setName('Randonnée nocturne sous les étoiles');
        $hike31->setDateSubscription(new \DateTime('2026-04-04 18:00:00'));
        $hike31->setDateEvent(new \DateTime('2026-04-26 21:00:00'));
        $hike31->setDuration(120);
        $hike31->setDescription("Partez pour une randonnée de nuit à la découverte des paysages sous les étoiles. Ambiance calme et immersive garantie. Prévoir lampe frontale.");
        $hike31->setNbMaxSubscription(8);
        $hike31->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike31->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike31->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike31->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Vallée du Canut']));
        $hike31->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike31->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike31->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike31->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike31->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike31->setPicture('hike31.jpg');
        $manager->persist($hike31);

        $hike32 = new Hike();
        $hike32->setName('Photographie animalière en forêt');
        $hike32->setDateSubscription(new \DateTime('2026-04-05 07:00:00'));
        $hike32->setDateEvent(new \DateTime('2026-04-27 08:30:00'));
        $hike32->setDuration(150);
        $hike32->setDescription("Partez à l’affût des animaux sauvages et capturez-les en photo. Une activité idéale pour les amateurs de nature et de photographie. Silence et patience requis.");
        $hike32->setNbMaxSubscription(6);
        $hike32->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike32->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike32->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike32->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Chizé']));
        $hike32->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike32->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike32->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike32->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike32->setPicture('hike32.jpg');
        $manager->persist($hike32);

        $hike33 = new Hike();
        $hike33->setName('Éco-rando nettoyage des sentiers');
        $hike33->setDateSubscription(new \DateTime('2026-04-06 09:00:00'));
        $hike33->setDateEvent(new \DateTime('2026-04-28 10:00:00'));
        $hike33->setDuration(120);
        $hike33->setDescription("Alliez randonnée et geste écologique en nettoyant les sentiers. Une activité utile et conviviale. Matériel fourni.");
        $hike33->setNbMaxSubscription(12);
        $hike33->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike33->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike33->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike33->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Parc naturel de la Brière']));
        $hike33->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike33->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike33->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike33->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike33->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike33->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike33->setPicture('hike33.jpg');
        $manager->persist($hike33);

        $hike34 = new Hike();
        $hike34->setName('Randonnée crépusculaire');
        $hike34->setDateSubscription(new \DateTime('2026-04-07 18:00:00'));
        $hike34->setDateEvent(new \DateTime('2026-04-29 19:30:00'));
        $hike34->setDuration(90);
        $hike34->setDescription("Profitez des lumières du coucher de soleil lors de cette randonnée. Ambiance chaleureuse et paysages magnifiques. Idéal pour se détendre.");
        $hike34->setNbMaxSubscription(7);
        $hike34->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Passée']));
        $hike34->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike34->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike34->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Baie des Trépassés']));
        $hike34->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike34->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike34->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'vanina']));
        $hike34->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike34->setPicture('hike34.jpg');
        $manager->persist($hike34);

        $hike35 = new Hike();
        $hike35->setName('Survie douce en pleine nature');
        $hike35->setDateSubscription(new \DateTime('2026-04-08 08:00:00'));
        $hike35->setDateEvent(new \DateTime('2026-04-30 09:00:00'));
        $hike35->setDuration(180);
        $hike35->setDescription("Initiez-vous aux bases de la survie en pleine nature. Apprenez à vous orienter et à utiliser les ressources naturelles. Activité ludique et enrichissante.");
        $hike35->setNbMaxSubscription(9);
        $hike35->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Activité en cours']));
        $hike35->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike35->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike35->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande']));
        $hike35->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike35->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike35->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike35->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike35->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienl']));
        $hike35->setPicture('hike35.jpg');
        $manager->persist($hike35);

        $hike36 = new Hike();
        $hike36->setName('Observation des insectes et biodiversité');
        $hike36->setDateSubscription(new \DateTime('2026-04-09 09:00:00'));
        $hike36->setDateEvent(new \DateTime('2026-05-01 11:00:00'));
        $hike36->setDuration(75);
        $hike36->setDescription("Découvrez le monde fascinant des insectes. Une activité pédagogique pour mieux comprendre la biodiversité locale. Accessible à tous.");
        $hike36->setNbMaxSubscription(8);
        $hike36->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike36->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike36->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike36->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Plaine de Niort']));
        $hike36->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike36->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike36->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike36->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike36->setPicture('hike36.jpg');
        $manager->persist($hike36);

        $hike37 = new Hike();
        $hike37->setName('Randonnée yoga en plein air');
        $hike37->setDateSubscription(new \DateTime('2026-04-10 08:00:00'));
        $hike37->setDateEvent(new \DateTime('2026-05-02 10:00:00'));
        $hike37->setDuration(120);
        $hike37->setDescription("Alliez marche et séance de yoga en pleine nature. Une expérience relaxante et revitalisante. Accessible à tous.");
        $hike37->setNbMaxSubscription(10);
        $hike37->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike37->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike37->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike37->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Lac de Grand-Lieu']));
        $hike37->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike37->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike37->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike37->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike37->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike37->setPicture('hike37.jpg');
        $manager->persist($hike37);

        $hike38 = new Hike();
        $hike38->setName('Trail matinal énergisant');
        $hike38->setDateSubscription(new \DateTime('2026-04-11 07:00:00'));
        $hike38->setDateEvent(new \DateTime('2026-05-03 08:00:00'));
        $hike38->setDuration(150);
        $hike38->setDescription("Un trail pour bien démarrer la journée. Parcours dynamique et stimulant. Idéal pour les sportifs.");
        $hike38->setNbMaxSubscription(9);
        $hike38->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Clôturée']));
        $hike38->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike38->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike38->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Presqu’île de Crozon - Cap de la Chèvre']));
        $hike38->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike38->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike38->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike38->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike38->setPicture('hike38.jpg');
        $manager->persist($hike38);

        $hike39 = new Hike();
        $hike39->setName('Balade contée en forêt');
        $hike39->setDateSubscription(new \DateTime('2026-04-12 09:00:00'));
        $hike39->setDateEvent(new \DateTime('2026-05-04 14:00:00'));
        $hike39->setDuration(90);
        $hike39->setDescription("Une randonnée ponctuée de contes et légendes locales. Ambiance immersive et ludique. Idéal pour petits et grands.");
        $hike39->setNbMaxSubscription(8);
        $hike39->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike39->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike39->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike39->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande']));
        $hike39->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike39->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike39->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike39->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike39->setPicture('hike39.jpg');
        $manager->persist($hike39);

        $hike40 = new Hike();
        $hike40->setName('Randonnée gourmande locale');
        $hike40->setDateSubscription(new \DateTime('2026-04-13 10:00:00'));
        $hike40->setDateEvent(new \DateTime('2026-05-05 12:00:00'));
        $hike40->setDuration(135);
        $hike40->setDescription("Associez randonnée et dégustation de produits locaux. Plusieurs pauses gourmandes sont prévues. Une expérience conviviale.");
        $hike40->setNbMaxSubscription(12);
        $hike40->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike40->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike40->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike40->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Vallée du Thouet']));
        $hike40->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike40->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'nicolas']));
        $hike40->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike40->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike40->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike40->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike40->setPicture('hike40.jpg');
        $manager->persist($hike40);

        $hike41 = new Hike();
        $hike41->setName('Randonnée bivouac léger');
        $hike41->setDateSubscription(new \DateTime('2026-04-14 08:00:00'));
        $hike41->setDateEvent(new \DateTime('2026-05-06 09:00:00'));
        $hike41->setDuration(240);
        $hike41->setDescription("Partez pour une randonnée avec initiation au bivouac léger. Apprenez à installer un campement simple en pleine nature. Expérience immersive garantie.");
        $hike41->setNbMaxSubscription(8);
        $hike41->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike41->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike41->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike41->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Vallée du Canut']));
        $hike41->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike41->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike41->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike41->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike41->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike41->setPicture('hike41.jpg');
        $manager->persist($hike41);

        $hike42 = new Hike();
        $hike42->setName('Observation des étoiles et astronomie');
        $hike42->setDateSubscription(new \DateTime('2026-04-15 19:00:00'));
        $hike42->setDateEvent(new \DateTime('2026-05-07 21:30:00'));
        $hike42->setDuration(120);
        $hike42->setDescription("Une sortie nocturne dédiée à l’observation du ciel. Découvrez constellations et étoiles dans un cadre naturel. Ambiance magique garantie.");
        $hike42->setNbMaxSubscription(10);
        $hike42->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike42->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike42->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike42->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Monts d’Arrée - Roc’h Trévezel']));
        $hike42->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike42->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'silvia']));
        $hike42->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'vanina']));
        $hike42->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike42->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike42->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike42->setPicture('hike42.jpg');
        $manager->persist($hike42);

        $hike43 = new Hike();
        $hike43->setName('Randonnée slow life');
        $hike43->setDateSubscription(new \DateTime('2026-04-16 09:00:00'));
        $hike43->setDateEvent(new \DateTime('2026-05-08 11:00:00'));
        $hike43->setDuration(75);
        $hike43->setDescription("Prenez le temps de marcher lentement et de profiter du moment présent. Une randonnée axée sur la détente et le bien-être. Accessible à tous.");
        $hike43->setNbMaxSubscription(7);
        $hike43->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike43->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike43->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike43->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Bords de l’Erdre']));
        $hike43->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike43->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike43->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike43->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike43->setPicture('hike43.jpg');
        $manager->persist($hike43);

        $hike44 = new Hike();
        $hike44->setName('Trail technique en sous-bois');
        $hike44->setDateSubscription(new \DateTime('2026-04-17 08:00:00'));
        $hike44->setDateEvent(new \DateTime('2026-05-09 09:30:00'));
        $hike44->setDuration(180);
        $hike44->setDescription("Un parcours technique en sous-bois avec obstacles naturels. Idéal pour les amateurs de trail expérimentés. Sensations garanties.");
        $hike44->setNbMaxSubscription(9);
        $hike44->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Clôturée']));
        $hike44->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike44->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike44->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Benon']));
        $hike44->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike44->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike44->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike44->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike44->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike44->setPicture('hike44.jpg');
        $manager->persist($hike44);

        $hike45 = new Hike();
        $hike45->setName('Balade artistique et croquis');
        $hike45->setDateSubscription(new \DateTime('2026-04-18 10:00:00'));
        $hike45->setDateEvent(new \DateTime('2026-05-10 14:00:00'));
        $hike45->setDuration(120);
        $hike45->setDescription("Initiez-vous au dessin en pleine nature. Plusieurs pauses sont prévues pour réaliser des croquis. Activité créative et relaxante.");
        $hike45->setNbMaxSubscription(6);
        $hike45->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike45->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike45->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Quimper']));
        $hike45->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Huelgoat']));
        $hike45->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike45->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'emilia']));
        $hike45->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike45->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike45->setPicture('hike45.jpg');
        $manager->persist($hike45);

        $hike46 = new Hike();
        $hike46->setName('Randonnée sportive en bord de mer');
        $hike46->setDateSubscription(new \DateTime('2026-04-19 08:00:00'));
        $hike46->setDateEvent(new \DateTime('2026-05-11 10:00:00'));
        $hike46->setDuration(210);
        $hike46->setDescription("Un parcours dynamique avec vues sur l’océan. Quelques passages exigeants mais gratifiants. Idéal pour les sportifs.");
        $hike46->setNbMaxSubscription(10);
        $hike46->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Activité en cours']));
        $hike46->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike46->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']));
        $hike46->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Côte sauvage du Croisic']));
        $hike46->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike46->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike46->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienq']));
        $hike46->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike46->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike46->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike46->setPicture('hike46.jpg');
        $manager->persist($hike46);

        $hike47 = new Hike();
        $hike47->setName('Marche sensorielle en forêt');
        $hike47->setDateSubscription(new \DateTime('2026-04-20 09:00:00'));
        $hike47->setDateEvent(new \DateTime('2026-05-12 11:00:00'));
        $hike47->setDuration(90);
        $hike47->setDescription("Une expérience immersive basée sur les sens. Marchez pieds nus sur certains tronçons et écoutez la nature. Activité originale et apaisante.");
        $hike47->setNbMaxSubscription(8);
        $hike47->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike47->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike47->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike47->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande']));
        $hike47->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike47->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'maud']));
        $hike47->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike47->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mathilde']));
        $hike47->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike47->setPicture('hike47.jpg');
        $manager->persist($hike47);

        $hike48 = new Hike();
        $hike48->setName('Éco-rando découverte de la faune');
        $hike48->setDateSubscription(new \DateTime('2026-04-21 08:30:00'));
        $hike48->setDateEvent(new \DateTime('2026-05-13 10:00:00'));
        $hike48->setDuration(135);
        $hike48->setDescription("Une randonnée axée sur la découverte de la faune locale. Apprenez à reconnaître les traces et habitats. Activité pédagogique.");
        $hike48->setNbMaxSubscription(9);
        $hike48->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
        $hike48->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'intermédiaire']));
        $hike48->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike48->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Mervent-Vouvant']));
        $hike48->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike48->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'david']));
        $hike48->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike48->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'antonin']));
        $hike48->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike48->setPicture('hike48.jpg');
        $manager->persist($hike48);

        $hike49 = new Hike();
        $hike49->setName('Randonnée zen au fil de l’eau');
        $hike49->setDateSubscription(new \DateTime('2026-04-22 09:00:00'));
        $hike49->setDateEvent(new \DateTime('2026-05-14 14:00:00'));
        $hike49->setDuration(75);
        $hike49->setDescription("Une balade relaxante le long de l’eau. Idéal pour se ressourcer et profiter du calme. Accessible à tous.");
        $hike49->setNbMaxSubscription(6);
        $hike49->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Passée']));
        $hike49->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'facile']));
        $hike49->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Niort']));
        $hike49->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Marais Poitevin - Coulon']));
        $hike49->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike49->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'camille']));
        $hike49->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike49->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'lena']));
        $hike49->setPicture('hike49.jpg');
        $manager->persist($hike49);

        $hike50 = new Hike();
        $hike50->setName('Grande randonnée exploration');
        $hike50->setDateSubscription(new \DateTime('2026-04-23 07:00:00'));
        $hike50->setDateEvent(new \DateTime('2026-05-15 08:00:00'));
        $hike50->setDuration(300);
        $hike50->setDescription("Une longue randonnée pour les passionnés d’aventure. Parcours varié et immersif. Prévoir une bonne endurance.");
        $hike50->setNbMaxSubscription(12);
        $hike50->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
        $hike50->setDifficulty($manager->getRepository(Difficulty::class)->findOneBy(['label' => 'expert']));
        $hike50->setCampus($manager->getRepository(Campus::class)->findOneBy(['name' => 'Chartres de Bretagne']));
        $hike50->setLocation($manager->getRepository(Location::class)->findOneBy(['name' => 'Forêt de Brocéliande']));
        $hike50->setPlanner($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'baptiste']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'arthur']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'thomas']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'adrienl']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'mael']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'clara']));
        $hike50->addParticipant($manager->getRepository(User::class)->findOneBy(['username' => 'yasmine']));
        $hike50->setPicture('hike50.jpg');
        $manager->persist($hike50);

        $manager->flush();
    }

}
