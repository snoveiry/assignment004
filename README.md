API LIST
------------

'/api/customers', "GET" , 'Retrieve all registered customers'

'/api/customer', "POST" , 'Register new customer'

'/api/customer/{ssn}', "GET" , 'Get a social security number of customer and return it'

'/api/customer/{ssn}', "PUT" , 'Update a customer with given social security number'

'/api/customer/{ssn}', "DELETE" , 'Delete a customer with given social security number'

'/api/account', "POST" , 'Register new bank account'

'/api/account/{accnumber}', "GET" , 'Get a bank account number and return all information of it(include customer)'

'/api/accounts/customer/{ssn}', "GET" , 'Get a social security number and return all bank accounts connected to it'

'/api/account/{accnumber}', "DELETE" , 'Delete a bank account with given bank account number'