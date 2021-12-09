<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAnswerQuestion", mappedBy="user", orphanRemoval=true)
     */
    private $questionAnswers;

    public function __construct()
    {
        parent::__construct();
        $this->questionAnswers = new ArrayCollection();
    }

    /**
     * @return Collection|UserAnswerQuestion[]
     */
    public function getQuestionAnswers(): Collection
    {
        return $this->questionAnswers;
    }

    public function addQuestionAnswer(UserAnswerQuestion $questionAnswer): self
    {
        if (!$this->questionAnswers->contains($questionAnswer)) {
            $this->questionAnswers[] = $questionAnswer;
            $questionAnswer->setUser($this);
        }

        return $this;
    }

    public function removeQuestionAnswer(UserAnswerQuestion $questionAnswer): self
    {
        if ($this->questionAnswers->contains($questionAnswer)) {
            $this->questionAnswers->removeElement($questionAnswer);
            // set the owning side to null (unless already changed)
            if ($questionAnswer->getUser() === $this) {
                $questionAnswer->setUser(null);
            }
        }

        return $this;
    }
}