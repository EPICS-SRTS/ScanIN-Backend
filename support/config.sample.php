<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

$config['base_url'] = '%base_url%';

$config['default']['hostname'] = '%hostname%';

$config['default']['username'] = '%username%';

$config['default']['password'] = '%password%';

$config['default']['database'] = '%database%';

$config['default']['dbdriver'] = 'pdo';


$config['default']['dsn'] = 'mysql:dbname='.$config['default']['database'].';host='.$config['default']['hostname'];