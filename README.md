# mytheresa-main

Steps to set up the application:

1. Clone the application using the command ' **git clone https://github.com/jayantpande03/mytheresa-main** '

2. **cd mytheresa-main**

3. Run the command "docker-compose up -d --build"




Steps to run the application:

1. To get the list of products - http://localhost:8080/products (GET). Query string parameters as defined in problem statement can be applied.

2. To add new product/products -  http://localhost:8080/addproduct (POST). Parameters accepted: data (in json format)
       
       eg: data={"products": [{"sku": "000001","name": "BV Lean leather ankle boots","category": "boots","price": 89000}]}





Steps to run the test cases:

1. Run the command "cat postInstall.sh | docker exec -i php74-container bash "




Explanation for decisions taken:

I have used php - symfony to set up the application, containerised using docker for seamless setup and machine independence. The database used is mysql.
The reason for selecting symfony is the ease with which rest api's can be implemented.
To add new products batch size of 200 is used in case the product list is huge (mentioned in problem statement- upto 20,0000).
