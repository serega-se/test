<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $answerText = null;

    /** Many features have one product. This is the owning side. */
    #[ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[JoinColumn(name: 'question_id', referencedColumnName: 'id')]
    /** @Ignore() */
    private Question|null $question = null;

    #[ORM\OneToOne(mappedBy: 'answer')]
    /** @Ignore() */
    private ?Statistic $statistic = null;

    #[ORM\OneToMany(mappedBy: 'answer', targetEntity: UserStatistic::class)]
    /** @Ignore() */
    private Collection $userStatistics;

    public function __construct()
    {
        $this->userStatistics = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    public function setAnswerText(string $answerText): self
    {
        $this->answerText = $answerText;

        return $this;
    }

    /**
     * @return Question|null
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * @param Question|null $question
     */
    public function setQuestion(?Question $question): void
    {
        $this->question = $question;
    }

    public function getStatistic(): ?Statistic
    {
        return $this->statistic;
    }

    public function setStatistic(?Statistic $statistic): self
    {
        // unset the owning side of the relation if necessary
        if ($statistic === null && $this->statistic !== null) {
            $this->statistic->setAnswer(null);
        }

        // set the owning side of the relation if necessary
        if ($statistic !== null && $statistic->getAnswer() !== $this) {
            $statistic->setAnswer($this);
        }

        $this->statistic = $statistic;

        return $this;
    }

    /**
     * @return Collection<int, UserStatistic>
     */
    public function getUserStatistics(): Collection
    {
        return $this->userStatistics;
    }

    public function addUserStatistic(UserStatistic $userStatistic): self
    {
        if (!$this->userStatistics->contains($userStatistic)) {
            $this->userStatistics->add($userStatistic);
            $userStatistic->setAnswer($this);
        }

        return $this;
    }

    public function removeUserStatistic(UserStatistic $userStatistic): self
    {
        if ($this->userStatistics->removeElement($userStatistic)) {
            // set the owning side to null (unless already changed)
            if ($userStatistic->getAnswer() === $this) {
                $userStatistic->setAnswer(null);
            }
        }

        return $this;
    }

}
