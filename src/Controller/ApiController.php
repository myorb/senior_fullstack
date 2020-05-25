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
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     *
     * The avarage score for an hotel
     * 
     * @Route("/api/average", name="average", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns score",
     *     @SWG\Schema(
     *          @SWG\Property(property="score", type="number"),
     *     )
     * )
     * @SWG\Parameter(
     *     name="hotelId",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Hotel id"
     * )
     */
    public function getAverage(Request $request): JsonResponse
    {
        $hotelId = $request->get('hotelId');

        if ($hotelId === null) {
            throw new \Exception('Expecting mandatory parameters!');
        }

        $hotel = $this->hotelRepository->find($hotelId);

        if (!$hotel) {
            throw new NotFoundHttpException('Hotel not found.');
        }

        $data = $this->reviewRepository->getAvg($hotelId);

        $response = new JsonResponse($data, Response::HTTP_OK);

        // cache for 3600 seconds
        $response->setSharedMaxAge(3600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    /**
     *
     * Get list of reviews
     * 
     * @Route("/api/reviews", name="review_list", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns score",
     *     @SWG\Schema(
     *          @SWG\Property(property="score", type="number"),
     *     )
     * )
     * @SWG\Parameter(
     *     name="hotelId",
     *     in="query",
     *     type="string",
     *     description="List of reviews for specific hotel"
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="string",
     *     description="Page number"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="string",
     *     description="Items per page"
     * )
     */
    public function getReviews(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $hotelId = $request->get('hotelId');
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $offset = ($page - 1) * $limit;

        if ($hotelId === null) {
            $reviews = $this->reviewRepository->findAll([], [], $limit, $offset);
        } else {
            $reviews = $this->reviewRepository->findBy(['hotel' => $hotelId], [], $limit, $offset);
        }

        $response = [];

        foreach ($reviews as $review) {
            $response[] = [
                'id' => $review->getId(),
                'score' => $review->getScore(),
                'comment' => $review->getComment(),
            ];
        }

        $data = $serializer->serialize($response, JsonEncoder::FORMAT);

        $response = new JsonResponse($data, Response::HTTP_OK, [], true);

        // cache for 3600 seconds
        $response->setSharedMaxAge(3600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    /**
     *
     * Create review for an hotel
     * 
     * @Route("/api/create-review", name="create_review", methods={"POST"})
     * @SWG\Response(
     *     response=201,
     *     description="Review created",
     *     @SWG\Schema(
     *          @SWG\Property(property="status", type="string"),
     *     )
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="hotelId", type="string"),
     *         @SWG\Property(property="score", type="number"),
     *         @SWG\Property(property="comment", type="string")
     *     )
     * )
     */
    public function postCreateReview(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $hotelId = $data['hotelId'] ?? '';
        $score = $data['score'] ?? 0;
        $comment = $data['comment'] ?? '';

        if (empty($hotelId) || empty($score)) {
            throw new \Exception('Expecting mandatory parameters!');
        }

        $hotel = $this->hotelRepository->find($hotelId);

        if (!$hotel) {
            throw new NotFoundHttpException('Hotel not found.');
        }

        $this->reviewRepository->saveReview($hotel, $score, $comment);

        return new JsonResponse(['status' => 'Created!'], Response::HTTP_CREATED);
    }
}
