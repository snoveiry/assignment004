<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class CustomerController extends AbstractController
{
    //return all customers
    #[Route('/api/customers', methods:"GET")]
    public function showAllCustomers(ManagerRegistry $doctrine): JsonResponse
    {
        $customers = $doctrine
            ->getRepository(Customer::class)
            ->findAll();
  
        $data = [];
  
        foreach ($customers as $customer) {
           $data[] = [
               'social_security_number' => $customer->getSocialSecurityNumber(),
               'name' => $customer->getName(),
               'address' => $customer->getAddress(),
           ];
        }
  
  
        return $this->json($data);
    }

    //add new customer
    #[Route('/api/customer', methods:"POST")]
    public function newCustomer(ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $customer = new Customer();
        $input = json_decode($request->getContent(), true);

        $customer->setName($input['name']);
        $customer->setSocialSecurityNumber($input['social_security_number']);
        $customer->setAddress($input['address']);
  
        /*if we want to get information from input parameters (form-data) we can do this:
        $customer->setName($request->get('name'));
        $customer->setSocialSecurityNumber($request->get('social_security_number'));
        $customer->setAddress($request->get('address'));*/
  
        $entityManager->persist($customer);
        $entityManager->flush();
  
        return $this->json('Created new customer successfully with SSN ' . $customer->getSocialSecurityNumber());
    }

    //return a specific customer based on social security number
    #[Route('/api/customer/{ssn}', methods:"GET")]
    public function showCustomer(ManagerRegistry $doctrine,string $ssn): JsonResponse
    {
        $customer = $doctrine->getRepository(Customer::class)->find($ssn);
  
        if (!$customer) {
            return $this->json('No customer found for entered social security number ' . $ssn, 404);
        }
  
        $data =  [
            'social_security_number' => $customer->getSocialSecurityNumber(),
            'name' => $customer->getName(),
            'address' => $customer->getAddress(),
        ];
          
        return $this->json($data);
    }

    //update a specific customer(not social security number) fields
    #[Route('/api/customer/{ssn}', methods:"PUT")]
    public function editCustomer(ManagerRegistry $doctrine, Request $request, string $ssn): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($ssn);
  
        if (!$customer) {
            return $this->json('No customer found for entered social security number ' . $ssn, 404);
        }

        $input = json_decode($request->getContent(), true);

        $customer->setName($input['name']);
        $customer->setAddress($input['address']);
        $entityManager->flush();
  
        $data =  [
            'social_security_number' => $customer->getSocialSecurityNumber(),
            'name' => $customer->getName(),
            'address' => $customer->getAddress(),
        ];
          
        return $this->json($data);
    }

    //delete a specific customer
    #[Route('/api/customer/{ssn}', methods:"DELETE")]
    public function deleteCustomer(ManagerRegistry $doctrine,string $ssn): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($ssn);
  
        if (!$customer) {
            return $this->json('No customer found for entered social security number ' . $ssn, 404);
        }
  
        $entityManager->remove($customer);
        $entityManager->flush();
  
        return $this->json('Deleted a customer successfully with social security number ' . $ssn);
    }
}
