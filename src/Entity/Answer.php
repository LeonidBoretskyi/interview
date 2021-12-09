<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $answerText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAnswerQuestion", mappedBy="answer")
     */
    private $userAnswer;

    public function __construct()
    {
        $this->userAnswer = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new \DateTime('now');
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    public function setAnswerText(string $answerText): self
    {
        $this->answerText = $answerText;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    protected function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|UserAnswerQuestion[]
     */
    public function getUserAnswer(): Collection
    {
        return $this->userAnswer;
    }

    public function addUserAnswer(UserAnswerQuestion $userAnswer): self
    {
        if (!$this->userAnswer->contains($userAnswer)) {
            $this->userAnswer[] = $userAnswer;
            $userAnswer->setAnswer($this);
        }

        return $this;
    }

    public function removeUserAnswer(UserAnswerQuestion $userAnswer): self
    {
        if ($this->userAnswer->contains($userAnswer)) {
            $this->userAnswer->removeElement($userAnswer);
            // set the owning side to null (unless already changed)
            if ($userAnswer->getAnswer() === $this) {
                $userAnswer->setAnswer(null);
            }
        }

        return $this;
    }
}
