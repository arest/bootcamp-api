<?php
namespace Deployer;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

require 'recipe/symfony3.php';
//require 'recipe/symfony.php';


// Project name
set('application', 'bootcamp_symfony');

// Project repository
set('repository', 'git@github.com:arest/bootcamp-api.git');
set('local_release_path', '.rsync_cache');
set('ssh_type', 'native');
//set('ssh_multiplexing', false );

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', ['app/config/parameters.yml']);
add('shared_dirs', ['var/logs', 'var/sessions']);

// Writable dirs by web server 
add('writable_dirs', ['var/logs', 'var/cache', 'var/sessions']);


// Hosts
host('ec2-34-210-86-236.us-west-2.compute.amazonaws.com')
	//->forwardAgent()
	//->stage('production')
    ->set('deploy_path', '~/projects/{{application}}')
    ->user('ubuntu')
	->set('branch', 'develop')
	->set('keep_releases', 1)
	//->identityFile()
	//->identityFile('~/.ssh/id_rsa.pub', '~/.ssh/andrea.restello@gmail.com', null, null)

; 
    
// Tasks

// task('build', function () {
//     run('cd {{release_path}} && build');
// });

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'database:migrate');

