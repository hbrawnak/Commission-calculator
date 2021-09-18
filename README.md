## **Commission Calculator**

Withdraw and deposit commission calculation with currency conversion

#### Run the script

````
-> cat input.csv
-> php script.php input.csv

Output
0.6
3
0
0.06
1.5
0
0.7
0.3
0.3
3
0
0
8613.29

````


#### UnitTest
````
-> phpunit tests/Service
-> phpunit tests/CalculatorTest.php
````

````
#####
-> phpunit tests/Service
#####

PHPUnit 6.5.14 by Sebastian Bergmann and contributors.

.................                                                 17 / 17 (100%)

Time: 874 ms, Memory: 10.00MB

OK (17 tests, 17 assertions)

#######
-> phpunit tests/CalculatorTest.php
#######

PHPUnit 6.5.14 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 82 ms, Memory: 10.00MB

OK (1 test, 1 assertion)

````

````
Note: 2 different commands of unittest because of the caching mechanism I have used in the project. 
When all tests run at a time, the app caches the value from CalculatorTest.php, 
then In CommissionTest.php expected output doesn't match. So the calculator test and 
service test should be run individually.
````

In these 6 days, I have tried to make the best output. I think it's not the best still. There is more room to improve. Commission.php class should be optimized and all other classes can be optimized. And more unittest for other cases. 
