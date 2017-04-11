#!/usr/bin/php
<?php

require_once('vendor/autoload.php');

if (!isset($argv[1])) {
    die("Usage: " . $argv[0] . " <domain>\n");
}

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$user = getenv('OPENPROVIDER_USERNAME');
$password = getenv('OPENPROVIDER_PASSWORD');
$domain = $argv[1];

$request = (new OP_Request())
    ->setCommand('retrieveZoneDnsRequest')
    ->setAuth([
        'username' => $user,
        'password' => $password
    ])
    ->setArgs([
        'name' => $domain,
        'withRecords' => true
    ]);

$api = new OP_API ('https://api.openprovider.eu');
$result = $api->process($request);

if ($result->getFaultString()) {
    die ($result->getFaultString() . "\n");
}

$records = $result->getValue()['records'];
foreach ($records as $record) {
    if (in_array($record['type'], ['SOA', 'NS'])) {
        continue;
    }

    $name = str_replace($domain, '', $record['name']);
    $name = (empty($name)) ? '@' : substr($name, 0, -1);

    $value = $record['value'];
    if ($record['type'] == 'MX') {
        $value = $record['prio'] . ' ' . $record['value'];
    }

    if (in_array($record['type'], ['CNAME', 'MX'])) {
        $value .= '.';
    }

    if ($record['type'] == 'TXT') {
        $value = '"' . $value . '"';
    }

    printf("%-20s\t%d\tIN\t%s\t%s\n",
        $name,
        $record['ttl'],
        $record['type'],
        $value
    );
}
