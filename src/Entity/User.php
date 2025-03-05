<?php

namespace App\Entity;

class User
{
    private string $id;
    private string $password;
    private string $email;
    private int $level;

    /**
     * @param string $id
     * @param string $password
     * @param string $email
     * @param int $level
     */
    public function __construct(string $id, string $password, string $email, int $level)
    {
        $this->id = $id;
        $this->password = $password;
        $this->email = $email;
        $this->level = $level;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }




}