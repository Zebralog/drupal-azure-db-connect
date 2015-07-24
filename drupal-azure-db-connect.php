<?php

$conn_string = getenv('MYSQLCONNSTR_DefaultConnection');

// We recommend using SSL encryption when using ClearDB.
//
// Get your certificates & keys from https://www.cleardb.com/dashboard and 
// copy them into sites/default/cleardb-certs. The resulting directory
// tree should look like this:
//
// sites/default/cleardb-certs
// - client-cert.pem.php
// - client-key.pem.php
// - cleardb-ca.pem.php
// Note that for security reasons we renamed all certificates to *.php.

// Use this for testing.
// $conn_string = 'Database=my-database-name;Data Source=eu-db-host-azure-west-a.cloudapp.net;User Id=521agff-my-user-id;Password=5KA%a--gqes3';

// Determine the database credentials.
$patterns = array(
  'host'      => 'Data Source=(.+?);',
  'username'  => 'User Id=(.+?);',
  'password'  => 'Password=(.+)$',
  'database'  => 'Database=(.+?);',
);
$db_settings = array();
foreach ($patterns as $key => $pattern) {
  $matches = array();
  preg_match("/$pattern/", $conn_string, $matches);
  if (isset($matches[1])) {
    $db_settings[$key] = $matches[1];
  }
}

if (count($db_settings) == 4) {
  $databases['default']['default'] = $db_settings;
}

// Check if certs & keys exists so that we can connect to cleardb via SSL.
$use_ssl = TRUE;
$db_ssl_conf = array(
  'key'     => 'cleardb-certs/client-key.pem.php',
  'cert'    => 'cleardb-certs/client-cert.pem.php',
  'ca-cert' => 'cleardb-certs/cleardb-ca.pem.php',
);
foreach($db_ssl_conf as $key => $filename) {
  if (!file_exists($filename)) {
    $use_ssl = FALSE;
  }
}

if (isset($databases) && $use_ssl) {
  $databases['default']['default']['driver options'] = array(
    PDO::MYSQL_ATTR_SSL_KEY   => $db_ssl_conf['key'],
    PDO::MYSQL_ATTR_SSL_CERT  => $db_ssl_conf['cert'],
    PDO::MYSQL_ATTR_SSL_CA    => $db_ssl_conf['ca-cert'],
  );
}
