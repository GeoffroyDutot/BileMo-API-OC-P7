<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setFirstName('Paul');
        $user->setLastName('Noroid');
        $user->setEmail('p.noroid@gmail.com');
        $user->setDateRegistration(new \DateTime('12-05-2021 12:06:38'));
        $user->setCompany($this->getReference('company'));
        $manager->persist($user);

        $user1 = new User();
        $user1->setFirstName('Juliette');
        $user1->setLastName('Langlaise');
        $user1->setEmail('j-langlais44@gmail.com');
        $user1->setDateRegistration(new \DateTime('15-05-2021 01:12:09'));
        $user1->setCompany($this->getReference('company'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstName('Pauline');
        $user2->setLastName('Maurisonne');
        $user2->setEmail('maurisonne-p@orange.fr');
        $user2->setDateRegistration(new \DateTime('15-05-2021 08:37:02'));
        $user2->setCompany($this->getReference('company1'));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setFirstName('Thomas');
        $user3->setLastName(null);
        $user3->setEmail('tom-pro@me.fr');
        $user3->setDateRegistration(new \DateTime('17-05-2021 17:38:29'));
        $user3->setCompany($this->getReference('company2'));
        $manager->persist($user3);

        $user4 = new User();
        $user4->setFirstName('Xavier');
        $user4->setLastName('Le Grand');
        $user4->setEmail('xlegrand@laposte.net');
        $user4->setDateRegistration(new \DateTime('17-05-2021 22:03:12'));
        $user4->setCompany($this->getReference('company'));
        $manager->persist($user4);

        $user5 = new User();
        $user5->setFirstName('Angelique');
        $user5->setLastName('Questembert');
        $user5->setEmail('angquestembert@gmail.com');
        $user5->setDateRegistration(new \DateTime('20-05-2021 10:32:46'));
        $user5->setCompany($this->getReference('company1'));
        $manager->persist($user5);

        $user6 = new User();
        $user6->setFirstName('Alexandre');
        $user6->setLastName('Duclos');
        $user6->setEmail('a.duclos@gmail.com');
        $user6->setDateRegistration(new \DateTime('19-05-2021 11:38:56'));
        $user6->setCompany($this->getReference('company1'));
        $manager->persist($user6);

        $user7 = new User();
        $user7->setFirstName('Joseph');
        $user7->setLastName('Valoran');
        $user7->setEmail('jos-valoran@gmail.com');
        $user7->setDateRegistration(new \DateTime('11-05-2021 18:29:11'));
        $user7->setCompany($this->getReference('company'));
        $manager->persist($user7);

        $user8 = new User();
        $user8->setFirstName('Marion');
        $user8->setLastName('Hernault');
        $user8->setEmail('hernault.marion@gmail.com');
        $user8->setDateRegistration(new \DateTime('08-05-2021 03:11:11'));
        $user8->setCompany($this->getReference('company2'));
        $manager->persist($user8);

        $user9 = new User();
        $user9->setFirstName('Celine');
        $user9->setLastName('Renaud');
        $user9->setEmail('c.renaud@gmail.com');
        $user9->setDateRegistration(new \DateTime('20-05-2021 19:26:58'));
        $user9->setCompany($this->getReference('company2'));
        $manager->persist($user9);

        $manager->flush();
    }
}
