#!/bin/bash

mkdir ../data 2> /dev/null

# deletes and remakes database files
rm ../data/perm.db 2> /dev/null

touch ../data/perm.db
sqlite3 ../data/perm.db < ./db-init.sql

chmod 777 ../data
chmod 666 ../data/*.db
