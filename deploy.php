<?php
namespace Deployer;

require 'vendor/deployer/deployer/recipe/laravel.php';
require 'vendor/deployer/recipes/recipe/npm.php';


// TODO remove this for production, removing no-dev so that we can migrate and seed in staging
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
set('writable_use_sudo', false); // Using sudo in writable commands

set('repository', 'git@get.gitlab.com:brattberg/lms.git');
set('git_tty', false);
add('writable_dirs', []);
add('shared_files', ['config.php']);

task('deploy:restart_services', function() {

	run('sudo service php7.2-fpm reload');
	run('sudo service nginx reload');
//	run('sudo supervisorctl restart all');
});

task('deploy:vendors', function () {
    run('cd {{release_path}} && {{bin/php }} {{bin/composer}} {{composer_options}}');
});


task('npm:run-build', function() {

	if (get('branch') == 'development') {
		$output = run("cd {{release_path}} && {{bin/npm}} run dev");
	} else {
		$output = run("cd {{release_path}} && {{bin/npm}} run production");
	}

	writeln('<info>'.$output.'</info>');
});

task('deploy:chown', function() {

    run('sudo chown ubuntu:www-data -R {{deploy_path}}');
});

set('http_user', 'ubuntu');

$branch = shell_exec('git rev-parse --abbrev-ref HEAD');
$branch = trim($branch);

host('betalms')
	->stage('beta')
    ->set('branch', $branch)
    ->set('keep_releases', 2)
	->set('deploy_path', '/home/ubuntu/beta-lms.generatetraining.ca');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:symlink', 'deploy:chown');
after('deploy:unlock', 'deploy:restart_services');


desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);