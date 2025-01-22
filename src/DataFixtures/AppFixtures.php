<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $encoder
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $infoProducts = [
            [
                "name" => "Kit d'hygiène recyclable",
                "brief_description" => "Pour une salle de bain éco-friendly",
                "description" => "description",
                "price" => "2499",
                "image" => "https://plus.unsplash.com/premium_photo-1661520867879-836166bc07be?q=80&w=1587&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            ],
            [
                "name" => "Shot Tropical",
                "brief_description" => "Fruits frais, pressés à froid",
                "description" => "description",
                "price" => "450",
                "image" => "https://images.unsplash.com/photo-1525904097878-94fb15835963?q=80&w=1740&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            ],
            [
                "name" => "Gourde en bois",
                "brief_description" => "50cl, bois d’olivier",
                "description" => "description",
                "price" => "1690",
                "image" => "https://barbacado.com/cdn/shop/files/gourde_bois2.jpg?v=1719487004&width=1200"
            ]
        ];

        foreach ($infoProducts as $item) {
            $product = new Products();
            $product
                ->setName($item['name'])
                ->setSlug($this->slugger->slug(strtolower($item['name'])))
                ->setBriefDescription($item['brief_description'])
                ->setDescription($item['description'])
                ->setPrice($item['price'])
                ->setImage($item['image'])
            ;
            $manager->persist($product);
        }

        $user = new User;
        $hash = $this->encoder->hashPassword($user, 'password');
        $user->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setEmail('user@gmail.com')
            ->setPassword($hash)
        ;

        $manager->flush();
    }
}
