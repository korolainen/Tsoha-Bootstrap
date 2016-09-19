#!/bin/bash

source config/environment.sh

echo "Luodaan tietokantataulut..."

ssh cs "
cd htdocs/$PROJECT_FOLDER/sql
cat drop_tables.sql create_tables.sql | psql -1 -f -
exit"

echo "Valmis!"
