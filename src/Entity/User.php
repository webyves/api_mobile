<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Exclude
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Serializer\Exclude
     */
    private $fbId;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Exclude
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fbName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Serializer\Exclude
     */
    private $fbToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserClient", mappedBy="user", orphanRemoval=true)
     * @Serializer\Exclude
     */
    private $userClients;

    public function __construct()
    {
        $this->userClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFbId(): ?string
    {
        return $this->fbId;
    }

    public function setFbId(string $fbId): self
    {
        $this->fbId = $fbId;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->fbId;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here - $this->plainPassword = null;
    }

    public function getFbName(): ?string
    {
        return $this->fbName;
    }

    public function setFbName(?string $fbName): self
    {
        $this->fbName = $fbName;

        return $this;
    }

    public function getFbToken(): ?string
    {
        return $this->fbToken;
    }

    public function setFbToken(?string $fbToken): self
    {
        $this->fbToken = $fbToken;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|UserClient[]
     */
    public function getUserClients(): Collection
    {
        return $this->userClients;
    }

    public function addUserClient(UserClient $userClient): self
    {
        if (!$this->userClients->contains($userClient)) {
            $this->userClients[] = $userClient;
            $userClient->setUserId($this);
        }

        return $this;
    }

    public function removeUserClient(UserClient $userClient): self
    {
        if ($this->userClients->contains($userClient)) {
            $this->userClients->removeElement($userClient);
            // set the owning side to null (unless already changed)
            if ($userClient->getUserId() === $this) {
                $userClient->setUserId(null);
            }
        }

        return $this;
    }
}
