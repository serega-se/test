<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $questionText = null;

    #[ORM\Column]
    private ?bool $hasMultianswer = null;

    /**
     * One product has many features. This is the inverse side.
     * @var Collection<int, Answer>
     */
    #[OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    private Collection $answers;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: UserStatistic::class)]
    /** @Ignore() */
    private Collection $userStatistics;

    #[Pure]
    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->userStatistics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(string $questionText): self
    {
        $this->questionText = $questionText;

        return $this;
    }

    public function isHasMultianswer(): ?bool
    {
        return $this->hasMultianswer;
    }

    public function setHasMultianswer(bool $hasMultianswer): self
    {
        $this->hasMultianswer = $hasMultianswer;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getAnswers(): ArrayCollection|Collection
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection|Collection $answers
     */
    public function setAnswers(ArrayCollection|Collection $answers): void
    {
        $this->answers = $answers;
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
            $userStatistic->setQuestion($this);
        }

        return $this;
    }

    public function removeUserStatistic(UserStatistic $userStatistic): self
    {
        if ($this->userStatistics->removeElement($userStatistic)) {
            // set the owning side to null (unless already changed)
            if ($userStatistic->getQuestion() === $this) {
                $userStatistic->setQuestion(null);
            }
        }

        return $this;
    }

}
