# php-slim-todo

[![Build Status](https://travis-ci.com/zerosuxx/php-slim-todo.svg?branch=master)](https://travis-ci.com/zerosuxx/php-slim-todo)
[![Coverage Status](https://coveralls.io/repos/github/zerosuxx/php-slim-todo/badge.svg?branch=master)](https://coveralls.io/github/zerosuxx/php-slim-todo?branch=master)

Készíts egy ToDo kezelő app-ot, mely az alábbi funkciókkal rendelkezik:


ToDo hozzáadás
ToDo módosítás
ToDo törlés
ToDo teljesítése
ToDo lista

Egy ToDo paraméterei:
```
- name (Wash the car)
- description (The effects of the last off-road experiment's should be washed off...)
- status (enum: incomplete, complete)
- due_at (2012-04-23T18:00:00.000Z)
```

A listában a legsürgősebb feladatok legyenek elöl s a teljesített elemeket ne jelenítse meg.


További részletek:
```
- A form POST requestet küldjön.
- A form elküldése után 301 redirect legyen a főoldalra, mert így nem küldi állandóan újra a form-ot a browser ha újratöltögeti a felhasználó az oldalt.
- A ToDo bejegyzéseit mysql-ben tárold.
- Ne fektess sok energiát a frontend-re, nem kell szépnek lennie. A backenden legyen a fókusz.
- Megbízhatunk a felhasználótól származó inputokban? (Nem)
(Nézzünk utána az XSS, a CSRF és az SQL injection fogalmainak és védekezzünk ellenük! Hogyan védekezzünk: https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet)
```