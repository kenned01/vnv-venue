<?php

namespace App\Entity;

class User
{

    public static int $ADMIN_USER_LEVEL = 1;

    private int $id;
    private string $name;
    private string $lastname;
    private string $email;
    private string $password;
    private ?string $phone;
    private ?string $phoneCode;
    private ?int $phoneValidation = 0;
    private ?string $membershipDueDate;
    private ?int $level;

    /**
     * @param string $id
     * @param string $name
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @param string|null $phone
     * @param int|null $phoneValidation
     * @param string|null $membershipDueDate
     * @param int|null $level
     */
    public function __construct(string $id, string $name, string $lastname, string $email, string $password, ?string $phone, ?int $phoneValidation, ?string $membershipDueDate, ?int $level, ?string $phoneCode)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->phoneValidation = $phoneValidation;
        $this->membershipDueDate = $membershipDueDate;
        $this->level = $level;
        $this->phoneCode = $phoneCode;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPhoneValidation(): int
    {
        return $this->phoneValidation;
    }

    public function setPhoneValidation(int $phoneValidation): void
    {
        $this->phoneValidation = $phoneValidation;
    }

    public function getMembershipDueDate(): ?string
    {
        return $this->membershipDueDate;
    }

    public function setMembershipDueDate(string $membershipDueDate): void
    {
        $this->membershipDueDate = $membershipDueDate;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getPhoneCode(): ?string
    {
        return $this->phoneCode;
    }

    public function setPhoneCode(?string $phoneCode): void
    {
        $this->phoneCode = $phoneCode;
    }



}