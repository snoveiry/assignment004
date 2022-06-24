<?php

use Doctrine\ORM\Mapping as ORM;

enum accT: string {
    case private = "PRIVATE";
    case organization = "ORGANIZATION";
}


/**
 * @Entity
 */

 class BankAccount
 {
    /** @Id @Column(type="string", length=11) */
    private $accountNumbr;

    /** @Column(type="string") */
    private $accountName;

    /** @Column(type="string",enumType: accT::class) */
    private accT $accounType;

    /** @Column(type="string") */
    private $currency;

    /** @Id @ManyToOne(targetEntity="Customer", inversedBy="bankAccounts") */
    private $customer;

   

    public function __construct($accountNumbr, $accountName,$accounType,$currency,$customer)
    {
        $this->accountNumbr = $accountNumbr;
        $this->accountName = $accountName;
        $this->accounType = $accounType;
        $this->currency = $currency;
        $this->customer = $customer;
    }

    
    public function getName()
    {
        return $this->name;
    }

    public function setname(?string $name)
    {
        $this->name = $name;
    }

    
    public function getSecurityNumber()
    {
        return $this->securityNumber;
    }

   
    public function setSecurityNumber(?string $securityNumber)
    {
        $this->phoneNumber = $securityNumber;
    }

    
 }


?>