<?php
	/* This program uses the the git wrapper found at https://github.com/cpliakas/git-wrapper */

	//get dependencies
	require_once __DIR__ . '/vendor/autoload.php';

	//use libraries
	use GitWrapper\GitWrapper;
	use Mailgun\Mailgun;

	//include our config file
	include 'config.php';

	//use our gitwrapper
	

	//get directories
	$dirs = glob(APPS, GLOB_ONLYDIR);

	//loop through the dirs
	function iterate_dirs($dirs){

		//create status
		$status = '';

		if ($dirs) {
			
			foreach ($dirs as $dir) 
{
				$wrapper = new GitWrapper();

				//find .git file in dir
				if (file_exists($dir .'/public/.git/')) {
					$dir .= '/public';
				} elseif (file_exists($dir . '/.git')) {
					//do nothing
				} else {
					echo($dir . ' does not contain a git repository');

					//php returns fatal errors so skip this if no repo
					continue;
				}
				
					$git_time = date('[m:d:y](G:i:s)');
					//start the time
					$status .= $git_time;

					$copy = $wrapper->workingCopy($dir);

					if ($copy->hasChanges()) {

						$status .= "\n";

						$status .= "\n";

						$status .="Changes in " . $dir;

						$status .= "\n";

						$status .= git_status($wrapper, $dir);

						git_commit($wrapper, $dir, $git_time);


					} else {
						$status .=  $dir . " Does not have changes!";
					}

					$status .= "\n";

					$status .= "\n";

					
				//$status = $wrapper->git('--git-dir=' . $dir .'/.git --work-tree=' . $dir . ' status');

			}


			
			send_mail_log($status);

		} else{
			//die if apps directory is not propery configured.
			die("APPS is not properly configued.");
		}


	}

	function git_commit($wrapper, $dir, $git_time){
		//check dir for get status and shit
		
			$wrapper->git('--git-dir=' . $dir .'/.git --work-tree=' . $dir . ' add --all' );
		
			$wrapper->git('--git-dir=' . $dir .'/.git --work-tree=' . $dir . ' commit -m "auto commit at ' . $git_time . '"' );

	}

	function git_status($wrapper, $dir){

		$status = $wrapper->git('--git-dir=' . $dir .'/.git --work-tree=' . $dir . ' status');
	
		return $status;

	}

	//sent an email

	function send_mail_log($email_content){	

		# First, instantiate the SDK with your API credentials and define your domain. 
		$mg = new Mailgun("key-15i5a455-ed224utmcx6k5ck84zkx6w7");
		$domain = "12southmusic.com";

		$subject = SUBJECT . date('[m:d:y](G:i:s)');

		# Now, compose and send your message.
		$mg->sendMessage($domain, array('from'    => EMAIL_FROM, 
		                                'to'      => EMAIL_TO_1, 
		                                'subject' => $subject, 
		                                'text'    => $email_content));

    }



     //fire everything
     iterate_dirs($dirs);
?>