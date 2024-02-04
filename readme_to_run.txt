Установка Docker с использованием Sail

cd /home/www/vet_app && ./vendor/bin/sail up
 
sail npm run dev

sail artisan migrate
sail artisan db:seed
