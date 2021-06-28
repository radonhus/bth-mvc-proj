Scrutinizer CI: [![Build Status](https://scrutinizer-ci.com/g/xlsson/bth-mvc-proj/badges/build.png?b=main)](https://scrutinizer-ci.com/g/xlsson/bth-mvc-proj/build-status/main) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xlsson/bth-mvc-proj/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/xlsson/bth-mvc-proj/?branch=main) [![Code Coverage](https://scrutinizer-ci.com/g/xlsson/bth-mvc-proj/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/xlsson/bth-mvc-proj/?branch=main)

Travis CI: [![Build Status](https://travis-ci.com/xlsson/bth-mvc-proj.svg?branch=main)](https://travis-ci.com/xlsson/bth-mvc-proj)

# ¥atzyBonanza

¥atzyBonanza is a student project made as part of the course "Object-oriented
web technologies" (mvc) at Blekinge Technical University. It is a web-based
Yatzy clone with a micro-community where members can either play alone
or challenge another member. The application is written in PHP, and uses the
Laravel framework.

![Yatzy](https://github.com/xlsson/bth-mvc-proj/blob/main/doc/design/yatzy_screenshot.png?raw=true)

## Features

¥atzyBonanza lets you play this addictive classic in either challenge or single
player mode. Both modes let you bet and win/lose coins. When you register, you
get a 100 ¥ start balance.

To win in a single player game, you will need to reach 250 points or more. Your
win is then twice the bet placed. If you fail to reach 250 points, the money bet
is lost.

Challenge mode lets you challenge any other player also registered. You decide
how much you want to bet (your account balance is the limit), and when you have
played your round, your bet is deducted from balance. When your friend logs in
to ¥atzyBonanza, they will be able to see your challenge, and can either accept
or decline. If the person you have challenged does not have enough coins to
match your bet, both yours and your friend's bet will be lowered to the sum that
they can afford. If the person who was challenged declines the challenge, your
bet will be paid back.

When the person you have challenged has finished their game, the winner is
calculated, and the winner wins the whole bet.

Game stats are stored and displayed both on your individual account page and in
the public highscore list (if you are good enough!), and bar charts help visualise
the statistics.

## Installation instructions

Your system needs the following for the application to work:
Git: https://git-scm.com/book/en/v2/Getting-Started-Installing-Git
PHP: https://www.php.net/manual/en/install.php
composer: https://getcomposer.org/download/
npm (included in Node.js): https://nodejs.org/en/

To run the application on your local server, follow these steps:
1. Run `git clone https://github.com/xlsson/bth-mvc-proj` to clone the repository.
2. Run `npm install` to install the dependencies listed in `package.json`.
3. Duplicate `.env.example` and name it `.env`.
4. Run `php artisan key:generate` to set the `APP_KEY` variable of the `.env`-file.
5. Run `php -S localhost:1234 -t public` to start a local PHP development server running at port 1234.
6. Navigate to `http://localhost:1234/`to access the application.
7. Before online publication, edit the rewrite parameters in `public/.htaccess` to reflect your URL.

Results and user data is stored in an SQLite database. The database contains
example users and results. If you prefer an empty database, run the SQL file
`database/db/clear_database.sql` to clear all results and users.

The repo includes test suites for unit testing using phpunit. For unit tests that
require a database to work, a separate database (database/db/test.db) is used.
More testing is possible through phpcs, phpmd, phpmetrics and phpstan, all of
which have config files in the root-folder of the repo. Run `make test` to run
all tests.
