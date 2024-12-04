<?php

namespace TestBlog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TestBlog\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="u_id", type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=100, name="u_name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true, name="u_email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, name="u_hash")
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=100, name="u_salt")
     */
    private $salt;

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

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }
}
