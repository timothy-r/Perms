#!/bin/bash

mkdir ../data 2> /dev/null

# deletes and remakes database files
rm ../data/test.db 2> /dev/null
rm ../data/dev.db 2> /dev/null
rm ../data/prod.db 2> /dev/null
rm ../data/perm.db 2> /dev/null

touch ../data/test.db
sqlite3 ../data/test.db < ./db-init.sql

touch ../data/dev.db
sqlite3 ../data/dev.db < ./db-init.sql

touch ../data/prod.db
sqlite3 ../data/prod.db < ./db-init.sql

touch ../data/perm.db
sqlite3 ../data/perm.db < ./db-init.sql

chmod 777 ../data
chmod 666 ../data/*.db
