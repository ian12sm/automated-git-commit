<?php

	/* This program uses the the git wrapper found at https://github.com/cpliakas/git-wrapper */

	//get dependencies
	require __DIR__ . '/vendor/autoload.php';

	//include our config file
	include 'config.php';

	//get directories
	$dirs = glob(APPS, GLOB_ONLYDIR);

	print_r($dirs);


?>