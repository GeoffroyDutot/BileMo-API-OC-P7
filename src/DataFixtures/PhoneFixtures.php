<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $phone = new Phone();
        $phone->setModel('BileMo O+ 2021');
        $phone->setBrand('BileMo');
        $phone->setEan('1573927937274');
        $phone->setDescription('BileMo O+ 2021 Edition.');
        $phone->setPrice(599.99);
        $phone->setColor('Cyan');
        $phone->setSize('6,7 pouces');
        $phone->setReleaseDate(new \DateTime('2021-02-01'));
        $manager->persist($phone);

        $phone1 = new Phone();
        $phone1->setModel('BileMo O+ 2021 Black Edition');
        $phone1->setBrand('BileMo');
        $phone1->setEan('1573927937275');
        $phone1->setDescription('BileMo O+ 2021 Black Edition.');
        $phone1->setPrice(599.99);
        $phone1->setColor('Black');
        $phone1->setSize('6,7 pouces');
        $phone1->setReleaseDate(new \DateTime('2021-04-01'));
        $manager->persist($phone1);

        $phone2 = new Phone();
        $phone2->setModel('BileMo O Lite 2021');
        $phone2->setBrand('BileMo');
        $phone2->setEan('1573927937273');
        $phone2->setDescription('BileMo O Lite 2021 Edition.');
        $phone2->setPrice(599.99);
        $phone2->setColor('Cyan');
        $phone2->setSize('5,7 pouces');
        $phone2->setReleaseDate(new \DateTime('2021-02-01'));
        $manager->persist($phone2);

        $manager->flush();
    }
}
