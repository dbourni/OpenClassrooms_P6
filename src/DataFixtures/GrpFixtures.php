<?php

namespace App\DataFixtures;

use App\Entity\Grp;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GrpFixtures extends Fixture
{
    public const GRP_GRAB = 'Grab';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getGroupData() as $name) {
            $group = new Grp();
            $group->setName($name);
            $manager->persist($group);

            if ($name == 'Grab') {
                $this->addReference(self::GRP_GRAB, $group);
            }
        }

        $manager->flush();
    }

    private function getGroupData(): array
    {
        return [
            'Grab',
            'Rotation',
            'Flip',
            'Rotation désaxée',
            'Slide',
            'One foot',
            'Old school'
        ];
    }
}
