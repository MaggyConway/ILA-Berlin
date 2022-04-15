<?php

/**
 * @file
 * Deployer main script.
 */

namespace Deployer;

require 'recipe/drupal8.php';

// Configuration.
set('repository', 'https://bitbucket.org/goldland/ila-2018.git');
set('allow_anonymous_stats', FALSE);
set('git_tty', TRUE);

// Drupal 8 shared dirs.
set('shared_dirs', [
  'web/sites/{{drupal_site}}/files',
  'private',
  'console/cache',
]);

// Drupal 8 shared files.
set('shared_files', [
  'web/sites/{{drupal_site}}/settings.php',
  'web/sites/{{drupal_site}}/services.yml',
]);

// Drupal 8 Writable dirs.
set('writable_dirs', [
  'web/sites/{{drupal_site}}/files',
  'private',
  'console/cache',
]);

host('172.104.132.131')
  ->user('admin')
  ->stage('production')
  ->set('deploy_path', '/var/www/production');

task('composer', 'composer install --no-dev');
task('drush', '
  cd {{release_path}}/web
  drush updatedb -y
  drush cr
');

task('deploy:build', [
  'composer',
  'drush',
]);

task('deploy', [
  'deploy:prepare',
  'deploy:lock',
  'deploy:release',
  'deploy:update_code',
  'deploy:shared',
  'deploy:symlink',
  'deploy:build',
  'deploy:unlock',
  'cleanup',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
