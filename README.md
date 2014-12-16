hangman
=======
The classic guessing game in api form using symfony2 and fosrestbundle.

build and run
-------------
 - `$ composer update`
 - `$ app/console doctrine:schema:create`
 - `$ phpunit -c app`
 - `$ app/console server:start`

This should install its dependencies, create the required sqlite database, run the tests and start a server on your local machine at port 8000. 
