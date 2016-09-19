#!/bin/bash

source config/environment.sh

echo "Lisätään testidata..."

ssh cs "
cd htdocs/$PROJECT_FOLDER/sql
psql < add_test_data.sql
exit"

echo "Valmis!"
