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
После запуска контейнеров вы можете подключиться к экземпляру MySQL в вашем приложении, установив для DB_HOST переменной среды в файле вашего приложения .env значение mysql.

sail artisan key:generate

sail artisan migrate

sail artisan db:seed

sail npm install

sail npm install -D tailwindcss postcss autoprefixer 

sail npm install -D @tailwindcss/forms

sail npm install alpinejs

sail npm run dev
