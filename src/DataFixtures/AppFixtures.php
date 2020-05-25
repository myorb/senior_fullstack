<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadHotels($manager);
        $manager->flush();
    }

    public function loadHotels($manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $hotel = new Hotel();
            $hotel->setId($i);
            $hotel->setName('Hotel Alexanderplatz ' . $i);
            $hotel->setAddress('Alexanderplatz ' . $i . ', 10409, Berlin');
            $manager->persist($hotel);

            $review = new Review();
            $review->setComment('Very nice stay');
            $review->setHotel($hotel);
            $review->setScore($i);
            $manager->persist($review);
        }
    }
}
