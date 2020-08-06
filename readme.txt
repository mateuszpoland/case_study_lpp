#in order to set up the project:

cd <some directory>
git clone https://github.com/mateuszpoland/case_study_lpp.git
cd case_study_lpp
composer install

php -S localhost:8080

Visit your browser and go into : localhost:8080 url
You should see 'Zadanie rekrutacyjne LPP'. If so, everything is working properly.
If not, first check log in your terminal, if 8080 port is not taken. In that case, use another one.

# to check the result of a first and second task(ItemService implementation + url validation), visit:
localhost:8080/getResult/1315475

# for the third task(BrandService implementation and Items sorting), please visit:

localhost:8080/getItems/winter

Most controller-wise logic is placed in Kernel.php, where HTTP routing is defined.

SUMMARY:

"Second responsibility is to sort the returning result from the item service in whatever way. 
Please write in the case study's summary if you find this approach correct or not. In both cases explain why."

I implemented ItemPriceSortedBrandService as a Strategy pattern. I think that sorting on the backend may not be necessary, but it is a valid solution.
Other way is to return always the same array and sort it on the front end (javascript) in high powered UI libraries like React, but it depends on 
certain situations - if this is a SPA and client is doing a lot of async fetches, it may be helpful to do some of the logic on the backend.


# to fire up unit tests:

vendor/bin/phpunit test/
----------------------------------------------------------------------------------

# Info

You need at least:

* PHP 5.3 or higher
* A text editor or IDE of your choice
* composer or autoloader of your choice (or provided spl autoloader)

# Knowledge

You will be tested within the following areas of PHP development:

* Basic understanding of PHP's OOP implementation, including interfaces and design patterns.
* Namespaces, Closures/Anonymous functions
* Reading resources from a local file system location
* Coping with JSON as data format

# The tasks

Listed below you will find a number of tasks to resolve. Each task should not take more than 45 to 60 minutes pure working time.
Please read all task before you start.
Please remember about the 'Boy Scout rule'.

## Implement a basic ItemService

Implement the interface for the Item Service. Please use the JSON file in the data directory as your datasource. 
Your implementation must read the resultset from the datasource and pass the values from the json file into the corresponding classes from the Entity namespace. 

The entities encapsulate each other:

(Brand) -[hasMany]-> (Items) -[hasMany] -> (Price)

The JSON file has a similar but not equal structure. Please take a deep look at both structures.

## Build a basic validator for the Item Entity

Your tasks is to build a validation mechanism for the Item Entity's url property.
Place the validation class in a proper position within the given architecture and ensure the value is valid.
It is up to you how you implement it and when to call it within the application's flow.

## Implement a way to get different implementations of the BrandServiceInterface

You can accomplish this in a few ways.
Please choose the variant you feel most comfortable with or you find most suitable, not the one that you think might be the fanciest of all.
You might want to write a second implementation for the BrandServiceInterface, but you don't have to.
If you need one, you can think of a "PriceOrderedBrandService" or an "ItemNameOrderedBrandService", which sort their results after receiving it from the item service.

Good luck!

