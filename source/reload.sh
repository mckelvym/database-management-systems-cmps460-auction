#!/bin/bash

host=$1
user=$2
pass=$3
db=$4
file=$5
cd ../data/
echo "Reloading data..."
mysql -h $host -u $user -p$pass $db < $file
echo "done."
