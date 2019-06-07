<?php

namespace App\DataFixtures;

use App\Controller\FigureController;
use App\DataFixtures\GrpFixtures;
use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    const FIXTURES_IMAGE_DIR = __DIR__ . '/../../src/DataFixtures/images/';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getFigureData() as [$name, $description, $videoUrl, $commentContent, $imageName]) {
            $figure = new Figure();
            $figure->setName($name);
            $figure->setDescription($description);
            $figure->setGrp($this->getReference(GrpFixtures::GRP_GRAB));
            $figure->setUser($this->getReference(UserFixtures::USER_ADMIN));
            $figure->setCreatedAt(new \DateTime());
            $figure->setSlug((new FigureController())->slugify($figure->getName()));

            $video = new Video();
            $video->setFigure($figure);
            $video->setUrl($videoUrl);
            $figure->addVideo($video);

            $picture = new Picture();
            $picture->setFigure($figure);
            $filename = sha1(uniqid(mt_rand(), true));
            $picture->setPath($filename.'.jpg');
            $pictureFile = new File(self::FIXTURES_IMAGE_DIR . $imageName);
            $pictureFile->move(__DIR__ . '/../../public/uploads', $filename.'.jpg');
            $picture->setIsMainPicture(true);
            $manager->persist($picture);
            $figure->addPicture($picture);

            $comment = new Comment();
            $comment->setFigure($figure);
            $comment->setUser($this->getReference(UserFixtures::USER_ADMIN));
            $comment->setCreatedAt(new \DateTime());
            $comment->setContent($commentContent);
            $figure->addComment($comment);

            $manager->persist($figure);
        }

        $manager->flush();
    }

    private function getFigureData(): array
    {
        return [
            ['Mute',
                'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
                'https://www.youtube.com/embed/M5NTCfdObfs',
                'Premier commentaire de la figure Mute',
                'mute.jpg'],
            ['Stalefish',
                'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.',
                'https://www.youtube.com/embed/8VsIZiM_Y6c',
                'Premier commentaire de la figure Stalefish',
                'stalefish.jpg'],
            ['Indy',
                'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
                'https://www.youtube.com/embed/yoAesRZcVTo',
                'Premier commentaire de la figure Indy',
                'indy.jpg'],
            ['Sade',
                'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.',
                'https://www.youtube.com/embed/KEdFwJ4SWq4',
                'Premier commentaire de la figure Sade',
                'sade.jpg'],
            ['Japan Air',
                'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.',
                'https://www.youtube.com/embed/jH76540wSqU',
                'Premier commentaire de la figure Japan Air',
                'japanair.jpg'],
            ['Frontflip',
                'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.',
                'https://www.youtube.com/embed/yoAesRZcVTo',
                'Premier commentaire de la figure Frontflip',
                'frontflip.jpg'],
            ['Backflip',
                'Un flip est une rotation verticale. Les backflips sont des rotations en arrière.',
                'https://www.youtube.com/embed/Yz4brafqk5A',
                'Premier commentaire de la figure Backflip',
                'backflip.jpg'],
        ];
    }

    public function getDependencies()
    {
        return array(
            GrpFixtures::class,
        );
    }
}