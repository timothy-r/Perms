#!/bin/bash

mkdir ../src/data 2> /dev/null

# deletes and remakes database files
rm ../src/data/test.db 2> /dev/null
rm ../src/data/dev.db 2> /dev/null
rm ../src/data/prod.db 2> /dev/null

touch ../src/data/test.db
sqlite3 ../src/data/test.db < db-init.sql

touch ../src/data/dev.db
sqlite3 ../src/data/dev.db < db-init.sql

touch ../src/data/prod.db
sqlite3 ../src/data/prod.db < db-init.sql

chmod 777 ../src/data
chmod 666 ../src/data/*.db
