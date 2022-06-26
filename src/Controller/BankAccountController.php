<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
//use Symfony\Component\Intl\Currencies;

class BankAccountController extends AbstractController
{

    //add new bank account
    #[Route('/api/account', methods:"POST")]
    public function newAccount(ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $account = new BankAccount();
        $input = json_decode($request->getContent(), true);

        $customer = $this->findCustomer($doctrine,$input['customer_social_security_number']);

        if (!$customer) {
            return $this->json('No customer found for entered social security number ' . $input['customer_social_security_number'], 404);
        }

        $account->setAccountNumber($this->generateAccountNumber(11));

        $account->setAccountType($input['account_type']);
        /*checking validation for account type*/
        if ($account->getAccountType()!= "PRIVATE" && $account->getAccountType()!= "ORGANIZATION")
        {
            return $this->json('Account type should be one of these values: [PRIVATE,ORGANIZATION]', 400);
        }

        $account->setAccountName($input['account_name']);

        $account->setCurrency($input['currency']);
        /*added this part to validate 
        $validCurrency = Currencies::exists($account->getCurrency());
        if (!$validCurrency){
            return $this->json('Currency code should be based on ISO 4217', 400);
        }*/

        $account->setCustomer($customer);
  
        $entityManager->persist($account);
        $entityManager->flush();
  
        return $this->json('Created new bank account with number ' . $account->getAccountNumber() . ' successfully for customer with SSN ' . $customer->getSocialSecurityNumber());
    }

    //return a specific bank account
    #[Route('/api/account/{accnumber}', methods:"GET")]
    public function showAccount(ManagerRegistry $doctrine,string $accnumber): JsonResponse
    {
        $account = $doctrine->getRepository(BankAccount::class)->find($accnumber);
  
        if (!$account) {
            return $this->json('No bank account found for entered account number ' . $accnumber, 404);
        }

        $customer = $account->getCustomer();

        $customerData = [
            'social_security_number' => $customer->getSocialSecurityNumber(),
            'name' => $customer->getName(),
            'address' => $customer->getAddress(),
        ];
  
        $data =  [
            'account_number' => $account->getAccountNumber(),
            'account_type' => $account->getAccountType(),
            'account_name' => $account->getAccountName(),
            'currency' => $account->getCurrency(),
            'customer' =>  $customerData,
        ];
          
        return $this->json($data);
    }

    //return all bank accounts for a specific customer
    #[Route('/api/accounts/customer/{ssn}', methods:"GET")]
    public function showCustomerAccount(ManagerRegistry $doctrine,string $ssn): JsonResponse
    {
        $accounts = $doctrine
            ->getRepository(BankAccount::class)
            ->findAll();

        $data = [];
        foreach ($accounts as $account) {
            if ($account->getCustomer()->getSocialSecurityNumber() == $ssn){
                $data[] = [
                    'account_number' => $account->getAccountNumber(),
                    'account_type' => $account->getAccountType(),
                    'account_name' => $account->getAccountName(),
                    'currency' => $account->getCurrency(),
                ];
            }
        }
          
        return $this->json($data);
    }

    //delete a specific customer
    #[Route('/api/account/{accnumber}', methods:"DELETE")]
    public function deleteAccountr(ManagerRegistry $doctrine,string $accnumber): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $account = $entityManager->getRepository(BankAccount::class)->find($accnumber);
  
        if (!$account) {
            return $this->json('No bank account found for entered number ' . $accnumber, 404);
        }
  
        $entityManager->remove($account);
        $entityManager->flush();
  
        return $this->json('Deleted a bank account successfully with number ' . $accnumber);
    }

    //find a customer
    public function findCustomer(ManagerRegistry $doctrine,string $ssn): Customer
    {
        $customer = $doctrine->getRepository(Customer::class)->find($ssn);
  
        if (!$customer) {
            return $this->null;
        }

        return $customer;
    }

    //generate a random 11 numbers to use as bank account number
    function generateAccountNumber($length) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomaccountNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomaccountNumber .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomaccountNumber;
    }
}

/*added this class to use enum validation for account type
enum AccType: string {
    case private = "PRIVATE";
    case organization = "ORGANIZATION";
}*/
