<?php
	/* This program uses the the git wrapper found at https://github.com/cpliakas/git-wrapper */

	//get dependencies
	require_once __DIR__ . '/vendor/autoload.php';

	//use libraries
	use GitWrapper\GitWrapper;
	use GitWrapper\Event\GitLoggerListener;
	use Monolog\Logger;
	use Monolog\Handler\StreamHandler;

	//include our config file
	include 'config.php';

	//use our gitwrapper
	

	//get directories
	$dirs = glob(APPS, GLOB_ONLYDIR);

	//loop through the dirs
	function iterate_dirs($dirs){

		if ($dirs) {
			
			foreach ($dirs as $dir) {

				$wrapper = new GitWrapper();

				//find .git file in dir
				if (file_exists($dir .'/public/.git/')) {
					$dir .= '/public';
				} elseif (file_exists($dir . '/.git')) {
					//do nothing
				} else {
					echo($dir . 'does not contain a git repository');

					//php returns fatal errors so skip this if no repo
					continue;
				}

/*				$wrapper->setEnvVar('HOME', '/path/to/a/private/writable/dir');*/
				
					$copy = $wrapper->workingCopy($dir);

					if ($copy->hasChanges()) {
					
						$status = $wrapper->git('--git-dir=' . $dir .'/.git --work-tree=' . $dir . ' status');
					
						print_r($status);

					} else {
						echo $dir . " Does not have changes!";
						echo "\n";
					}

				//$status = $wrapper->git('--git-dir=' . $dir .'/.git --work-tree=' . $dir . ' status');

			}

		} else{
			//die if apps directory is not propery configured.
			die("APPS is not properly configued.");
		}

	}

	iterate_dirs($dirs);

	function git_status(){
		//check dir for get status and shit
	}


?>