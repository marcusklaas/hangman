hangman
=======
The classic guessing game in api form using symfony2 and fosrestbundle.

build and run
-------------
 - `$ composer install`
 - `$ app/console doctrine:schema:create`
 - `$ phpunit -c app`
 - `$ app/console server:start`

This should install its dependencies, create the required sqlite database, run the tests and start a server on your local machine at port 8000. 

api
---

- `GET /games` lists the ids of all the games
- `POST /games` starts a new game
- `GET /games/:id` retrieves the details of a game, if it exists
- `POST /games/:id` with body `char=c` guesses letter `c`
