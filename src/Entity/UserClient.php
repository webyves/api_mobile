<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserClientRepository")
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "user_client_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true,
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list"})
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "user_client_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list", "detail"})
 * )
 * @Hateoas\Relation(
 *      "create",
 *      href = @Hateoas\Route(
 *          "user_client_create",
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list", "detail"})
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "user_client_list",
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"detail"})

 * )
 */
class UserClient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "detail"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "detail"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userClients")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude
     */
    private $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
