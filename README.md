How To Install
========================

$cd project_folder

$composer install
$yarn install
$yarn encore prod
add file ".env.local"
create DATABASE_URL in ".env.local"
$php bin/console doctrine:database:create
register through form or create user with command
add admin role to user with command
$php bin/console fos:user:promote testuser ROLE_ADMIN
now you can use app!

