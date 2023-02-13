<?php

namespace App\Entity;

use App\Repository\UserStatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatisticRepository::class)]
#[ORM\Index(fields: ["ipaddr"])]
#[ORM\UniqueConstraint(
    name: 'ipaddr_question_idx',
    columns: ['ipaddr','question_id']
)]
class UserStatistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ipaddr = null;

    #[ORM\ManyToOne(inversedBy: 'userStatistics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'userStatistics')]
    private ?Answer $answer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpaddr(): ?string
    {
        return $this->ipaddr;
    }

    public function setIpaddr(string $ipaddr): self
    {
        $this->ipaddr = $ipaddr;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

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
