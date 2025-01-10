<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $infoProducts = [
            [
                "name" => "Kit d'hygiène recyclable",
                "brief_description" => "Pour une salle de bain éco-friendly",
                "description" => "description",
                "price" => "2499",
                "image" => "https://s3-alpha-sig.figma.com/img/78e3/e660/0f07c28090abf9ac0d263bf4473ba9a6?Expires=1737331200&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=gvQLfzq7oGzBNk8aaxdvJuZGC3VbnBzg-MaeOKwMKOcgrlRaqguf093P3AY18w~5DQLHBMxmZecx4WOHq~vMcOTLKVoZlsGPUxAgBlATzWH6TNB6gtMHtxMZYD7DPWh2CF2iLowDIW3fciprRTIPn57GIXNPPh07MiqfRcm2Lx8CgFZWNBmYud2q9GQkLPpgJh6n5w97ICYBtmBH0KG4yVzk5Q3Y-j42j8YA~ELuqp3ybRfoRpoiyNlq-n~Jp4Z7S-CdxS6Ge269ZYl1BXGvk8E9OF5UBEqyRGON7oOUn-NQwcEe13CfxCeJUECR2UDfMDKJ0yPGXKKXMcnPDSODpw__"
            ],
            [
                "name" => "Shot Tropical",
                "brief_description" => "Fruits frais, pressés à froid",
                "description" => "description",
                "price" => "450",
                "image" => "https://s3-alpha-sig.figma.com/img/0d36/171c/14b95ab56656af06d7a69ab2d9ee44d0?Expires=1737331200&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=i--8LcY1JbyS9mtGzmeWCQ-yRKuWljvCH3JYS2HbFj5SxjnQm6J1tKhI9-LjLSgTsyZoG75lL6Mvz20jQxTk59O06jqub3PMw1a9qGatIVOpAQvTi59RwMSMaTNHPn9ggEH-LJt85cdSkEajeZ19bOwxdD2CUi3o-T7Eox3I1NUNpZ~imP2qAy8aUtqKE8y3YGh0oNhiy6Zz8PTrNuUN4uVHI1bC9JCMRA2op~-XwqORnWhf-WExTP7xR1Rh81wmF8~vvL-SPEY1l7b33l3hAxM0w4dRbGbCifsbc2DVq7LSPErU3fXCLjxDRNfIlZ1Xd9Xn2rfX-G96RP6kEPWApg__"
            ],
            [
                "name" => "Gourde en bois",
                "brief_description" => "50cl, bois d’olivier",
                "description" => "description",
                "price" => "1690",
                "image" => "https://s3-alpha-sig.figma.com/img/64e3/47aa/5c542819963e653209f118071a79567b?Expires=1737331200&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=hTcMV8X7MmNNSvFkV8ntFQH6uvLkKrGoRtf1YR3CVVWplIoanZp5m-5CWFivxkk-5~wO74Ft4VHUPcD6mjiGBKS4-laiXu3Nvd~H4DXE-LzREuKOATIs5FBbz9Uh2ADirEPa--8gYDS9EIzrnPvjHyMngEDQ3tPWbbayuA1vvsT4iQFGIu9zASgKdLB4CAziMRVLRIGci8E6ddYa-Ni8u8sfQCmqz7FveD0fWB7eH~mD2pySppcYhxViITcmqSa94mZmjZeafYFXBoNuCotV01Sy6SWAsmx-40tH07B9ma9YYPdDcdjWIeBHkZn9Bs8nyxdWKmlgIvASyK1Y2ND9iA__"
            ]
        ];

        foreach ($infoProducts as $item) {
            $product = new Products();
            $product
                ->setName($item['name'])
                ->setSlug($this->slugger->slug($item['name']))
                ->setBriefDescription($item['brief_description'])
                ->setDescription($item['description'])
                ->setPrice($item['price'])
                ->setImage($item['image'])
            ;
            $manager->persist($product);
        }

        $manager->flush();
    }
}
