<?php

namespace Deployer;

require 'recipe/codeigniter.php';
require 'contrib/rsync.php';
require 'contrib/crontab.php';

set('keep_releases', 5);
set('copy_dirs', ['app', 'public', 'system']);
set('application', 'ews');
set('rsync_src', __DIR__);
set('rsync_dest', '{{release_path}}');

set('shared_dirs', ['writable']);
add('shared_files', ['.env']);

set('writable_dirs', ['writable']);
set('writable_mode', 'chmod');
set('writable_recursive', true);
set('writable_chmod_mode', '0777');

add('rsync', [
    'exclude' => [
        '*.csv',
        '*.log',
        '.idea',
        '.DS_Store',
        '.env',
        '.gitignore',
        '*.md',
        'deploy.php',
    ],
]);

// Hosts
host('10.194.73.95')
    ->set('remote_user', 'root')
    ->set('labels', ['stage' => 'staging'])
    ->set('deploy_path', '/usr/share/nginx/{{application}}')
    ->set('rsync_dest', '{{release_path}}');

host('10.194.73.94')
    ->set('remote_user', 'root')
    ->set('labels', ['stage' => 'prod'])
    ->set('deploy_path', '/usr/share/nginx/{{application}}')
    ->set('rsync_dest', '{{release_path}}');

// Tasks
desc("Deployment Task");
task('deploy', [
    'deploy:setup',
    'deploy:lock',
    'deploy:release',
    'deploy:copy_dirs',
    'rsync',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'deploy:cleanup',
    'restart-nginx-fpm',
    'deploy:success'
]);

task('restart-nginx-fpm', function () {
    run('systemctl restart nginx');
    run('systemctl restart php-fpm');
});

after('deploy:failed', 'deploy:unlock');

after('deploy:success', 'crontab:sync');

after('deploy:success', 'db-changes');

add('crontab:jobs', [
    '0 10 * * * cd {{current_path}}/public && {{bin/php}} index.php cron morning >> /dev/null 2>&1',
    '0 23 * * * cd {{current_path}}/public && {{bin/php}} index.php cron night >> /dev/null 2>&1',
]);

task('db-changes', function () {
    run('cd {{current_path}} && php spark migrate');
});

task('logs', function () {
    run('cat /usr/share/nginx/ews/current/writable/logs/log-2022-06-*.log');
});
