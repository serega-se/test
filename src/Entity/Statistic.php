<?php

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticRepository::class)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $countAnswers = null;

    #[ORM\Column(nullable: true)]
    private ?int $fraction = null;

    #[ORM\OneToOne(inversedBy: 'statistic')]
    private ?Answer $answer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountAnswers(): ?int
    {
        return $this->countAnswers;
    }

    public function setCountAnswers(?int $countAnswers): self
    {
        $this->countAnswers = $countAnswers;

        return $this;
    }

    public function getFraction(): ?int
    {
        return $this->fraction;
    }

    public function setFraction(int $fraction): self
    {
        $this->fraction = $fraction;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
