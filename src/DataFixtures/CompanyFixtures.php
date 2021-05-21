<?php


namespace App\DataFixtures;


use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompanyFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {
        $company = new Company();
        $company->setName('Amazone');
        $company->setEmail('admin-amazone@aws.fr');
        $password = $this->encoder->encodePassword($company, 'Amz0nePass2021!');
        $company->setPassword($password);
        $manager->persist($company);
        $this->addReference('company', $company);

        $company1 = new Company();
        $company1->setName('Fnak');
        $company1->setEmail('contact-fnak@gmail.com');
        $password1 = $this->encoder->encodePassword($company1, 'FnaK2020@0');
        $company1->setPassword($password1);
        $manager->persist($company1);
        $this->addReference('company1', $company1);

        $company2 = new Company();
        $company2->setName('TaupeAchat');
        $company2->setEmail('taupachat@gmail.com');
        $password2 = $this->encoder->encodePassword($company2, 'TaupeAchate2021!');
        $company2->setPassword($password2);
        $manager->persist($company2);
        $this->addReference('company2', $company2);

        $manager->flush();
    }
}
