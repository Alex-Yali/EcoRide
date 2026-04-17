#!/bin/bash

echo "Import MongoDB..."

mongoimport \
  --db ecoride \
  --collection preferences \
  --file /docker-entrypoint-initdb.d/ecoride.preferences.json \
  --jsonArray

echo "Import terminé"