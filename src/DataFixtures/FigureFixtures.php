<?php

namespace App\DataFixtures;

use App\DataFixtures\GrpFixtures;
use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getFigureData() as [$name, $description, $header]) {
            $figure = new Figure();
            $figure->setName($name);
            $figure->setDescription($description);
            $figure->setGrp($this->getReference(GrpFixtures::GRP_GRAB));
            $figure->setUser($this->getReference(UserFixtures::USER_DBOURNI));
            $figure->setCreatedAt(new \DateTime());
            $manager->persist($figure);
        }

        $manager->flush();
    }

    private function getFigureData(): array
    {
        return [
            ['Mute', 'Description de la figure Mute exemple', 'placeholder.jpg'],
            ['Sade', 'Description de la figure Sade exemple', 'placeholder.jpg'],
        ];
    }

    public function getDependencies()
    {
        return array(
            GrpFixtures::class,
        );
    }
}