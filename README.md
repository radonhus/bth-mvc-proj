Scrutinizer CI: [![Build Status](https://scrutinizer-ci.com/g/radonhus/bth-mvc-proj/badges/build.png?b=main)](https://scrutinizer-ci.com/g/radonhus/bth-mvc-proj/build-status/main) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/radonhus/bth-mvc-proj/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/radonhus/bth-mvc-proj/?branch=main) [![Code Coverage](https://scrutinizer-ci.com/g/radonhus/bth-mvc-proj/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/radonhus/bth-mvc-proj/?branch=main)

Travis CI: [![Build Status](https://travis-ci.com/radonhus/bth-mvc-proj.svg?branch=main)](https://travis-ci.com/radonhus/bth-mvc-proj)

# ¥atzyBonanza

A webbased Yatzy clone with a mini community where members can either play alone
or challenge another member. Written in PHP using the Laravel framework.

![Yatzy](https://github.com/radonhus/bth-mvc-proj/blob/main/doc/design/yatzy_screenshot.png?raw=true)

¥atzyBonanza lets you play this addictive classic in either challenge or single
player mode. Both modes let you bet and win/lose coins. When you register, you
get a 100 ¥ start balance.

To win in a single player game, you will need to reach 250 points or more. Your
win is then twice the bet placed. If you fail to reach 250 points, the money bet
is lost.

Challenge mode lets you challenge any other player also registered. You decide
how much you want to bet (your account balance is the limit), and when you have
played your round, your bet is deducted from balance. When your friend logs in
to YatzyBonanza, they will be able to see your challenge, and can either accept
or decline. If the person you have challenged does not have enough coins to
match your bet, both yours and your friend's bet will be lowered to the sum that
they can afford. If the person who was challenged declines the challenge, your
bet will be paid back.

When the person you have challenged has finished their game, the winner is
calculated, and the winner wins the whole bet.

Game stats are stored and displayed both on your individual account page and in
the public highscore list (if you are good enough!), and bar charts help visualise
the numbers. Among other things, there is also a list keeping track of the
richest (and most successful) players.
