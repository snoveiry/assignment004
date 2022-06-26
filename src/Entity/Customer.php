<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255,name:'socialSecurityNumber')]
    private $socialSecurityNumber;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\OneToMany(targetEntity:"BankAccount",mappedBy:"customer",indexBy:"accountNumber")]
    private $accountNumbers;

    public function __construct()
    {
        $this->accountNumbers = new ArrayCollection();
    }

    public function getAccountNumbers(): Collection
    {
        return $this->accountNumbers;
    }

    public function addAccountNumber($accountNumber, $accountType,$accountName,$currency)
    {
        $this->accountNumbers[$accountNumber] = new BankAccount($accountNumber, $accountType,$accountName,$currency, $this);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSocialSecurityNumber(): ?string
    {
        return $this->socialSecurityNumber;
    }

    public function setSocialSecurityNumber(string $socialSecurityNumber): self
    {
        $this->socialSecurityNumber = $socialSecurityNumber;

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
}
