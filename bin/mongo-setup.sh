#! /bin/bash

mongoimport --host localhost --db champions --collection matches --file data/matches.json

mongo localhost:27017/champions data/homeTable.js
mongo localhost:27017/champions data/awayTable.js

php bin/footballer -f
