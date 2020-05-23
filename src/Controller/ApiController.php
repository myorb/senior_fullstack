<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{

    private $reviewRepository;
    private $hotelRepository;

    public function __construct(
        ReviewRepository $reviewRepository,
        HotelRepository $hotelRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->hotelRepository = $hotelRepository;
    }

    /**
     * @Route("/api/average", name="average")
     */
    public function getAverage(Request $request): JsonResponse
    {
        $hotelId = $request->get('hotelId');

        if ($hotelId === null) {
            throw new \Exception('Expecting mandatory parameters!');
        }

        $hotel = $this->hotelRepository->find($hotelId);

        if (!$hotel) {
            throw new \Exception('Hotel not found.');
        }

        $data = $this->reviewRepository->getAvg($hotelId);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/reviews", name="review_list")
     */
    public function getReviews(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $hotelId = $request->get('hotelId');

        if ($hotelId === null) {
            $reviews = $this->reviewRepository->findAll();
        } else {
            $reviews = $this->reviewRepository->findBy(['hotel_id' => $hotelId]);
        }

        $data = $serializer->serialize($reviews, JsonEncoder::FORMAT);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/create-review", name="create_review")
     */
    public function postCreateReview(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $hotelId = $data['hotelId'];
        $score = $data['score'];
        $comment = $data['comment'];

        if (empty($hotelId) || empty($score) || empty($comment)) {
            throw new \Exception('Expecting mandatory parameters!');
        }

        $hotel = $this->hotelRepository->find($hotelId);

        if (!$hotel) {
            throw new \Exception('Hotel not found.');
        }

        $this->reviewRepository->saveReview($hotelId, $score, $comment);

        return new JsonResponse(['status' => 'Created!'], Response::HTTP_CREATED);
    }
}
