<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Statistic;
use App\Entity\UserStatistic;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class StatisticController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        LoggerInterface $logger
    )
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    #[Route('/api/v1/statistics', name: 'app_statistic', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $result = [];
        $questions = $this->em->getRepository(Question::class)->findAll();
        foreach ($questions as $question) {

            $answersStatistics = $this->em->getRepository(Statistic::class)->getAnswersStatictics($question->getId());
            $result[] =
                [
                    "question" => $question->getQuestionText(),
                    "answers" =>  $answersStatistics
                ];
        }

        return new JsonResponse($result, Response::HTTP_OK, []);
    }

    #[Route('/api/v1/statistics', name: 'app_statistic_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        if ($content = $request->getContent()) {
            $this->logger->info($content);
            $ipaddr = $request->getClientIp();
            $data = json_decode($content, true);

            if (empty($data)) {
                throw new BadRequestHttpException('data cannot be empty');
            }

            $this->em->getRepository(UserStatistic::class)->updateStat($ipaddr, $data);
            $this->em->getRepository(Statistic::class)->updateCounts($data);

            return new JsonResponse([], Response::HTTP_CREATED, []);
        }
        return new JsonResponse([], Response::HTTP_NOT_MODIFIED, []);
    }

}
