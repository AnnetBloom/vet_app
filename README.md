Установка Docker с использованием Sail

git clone git@github.com:AnnetBloom/vet_app.git

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up -d

настроить env, настроить алиас sail. 
После запуска контейнеров вы можете подключиться к экземпляру MySQL в вашем приложении, установив для DB_HOSTпеременной среды в файле вашего приложения .envзначение mysql.

sail artisan key:generate

sail artisan migrate

sail artisan db:seed

sail npm install

npm install -D tailwindcss postcss autoprefixer

sail npm install alpinejs

sail npm run dev
