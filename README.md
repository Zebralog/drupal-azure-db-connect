# Drupal & Azure: Auto connect to linked database

When running drupal as a web app in the azure cloud, this snippet spares you the manual database configuration.

## Installation

In your drupal root, copy this directory into sites/default. Include drupal-azure-db-connect.php in your settings.php.

    // in your settings.php
    // ...
    include_once('drupal-azure-db-connect.php');

## Use SSL for requests to cleardb

We recommend using SSL encryption when using ClearDB.

Get your certificates & keys from https://www.cleardb.com/dashboard and 
copy them into sites/default/cleardb-certs. The resulting directory
tree should look like this:

    sites/default/cleardb-certs
    - client-cert.pem.php
    - client-key.pem.php
    - cleardb-ca.pem.php

Note that for security reasons we renamed all certificates to *.php. Once your (valid!) certificates are available, SSL encryption will be used automatically.
