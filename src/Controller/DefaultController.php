<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index(HotelRepository $hotelRepository)
    {
        $hotel = $hotelRepository->findOneBy([]);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'hotelId' => $hotel->getId()
        ]);
    }
}
