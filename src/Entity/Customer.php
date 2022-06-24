<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="customer")
 * @ORM\Entity()
 */

 class Customer
 {
    /** @Column(type="string") */
    private $name;

    /** @Id @Column(type="string") */
    private $socialNumber;

    /**
     * @OneToMany(targetEntity="BankAccount", mappedBy="customerSocialNumber", cascade={"ALL"}, indexBy="accountNumbr")
     */
    private $bankAccounts;

    public function addBankAccount($accountNumber, $accountName,$accountType,$currency)
    {
        $this->bankAccounts[$accountNumber] = new BankAccount($accountNumber, $accountName, $accountType,$currency,$this);
    }

    public function __construct($name, $socialNumber)
    {
        $this->name = $name;
        $this->socialNumber = $socialNumber;
    }

    
    public function getName()
    {
        return $this->name;
    }

    public function setname(?string $name)
    {
        $this->name = $name;
    }

    
    public function getSocialNumber()
    {
        return $this->socialNumber;
    }

   
    public function setSocialNumber(?string $socialNumber)
    {
        $this->socialNumber = $socialNumber;
    }

    
 }


?>