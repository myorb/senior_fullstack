<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Review::class);
        $this->manager = $manager;
    }

    public function saveReview(Hotel $hotel, $score, string $comment)
    {
        $model = new Review();

        $model
            ->setHotel($hotel)
            ->setScore($score)
            ->setComment($comment);

        $this->manager->persist($model);
        $this->manager->flush();
        return true;
    }

    public function getAvg($hotelId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT avg(score) as score FROM review WHERE hotel_id = :hotelId';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['hotelId' => $hotelId]);

        return $stmt->fetch();
    }

    public function toJson()
    {
        return [
            'id' => $this->id,
        ];
    }
}
