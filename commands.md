#### Criar projeto
    - composer create-project laravel/laravel:8.0 site

#### Run Project
    - php artisan serve

#### CLI (command line interface) artisan
    - php artisan list

#### Controller via artisan
    - php artisan make:controller NomeController
    - php artisan make:controller --resource NomeController

#### Model via artisan
    - php artisan make:model NomeModel
    - php artisan make:model NomeModel -cms (controller/migration/seeder)

#### Database - migrations
    - php artisan migrate  (gera as migrations)
    - php artisan make:migration create_nome-da-tabela_table  (cria uma tabela)
    - php artisan make:migration create_nome-da-tabela1_nome-da-tabela2_table  (cria uma tabela com duas chaves estrangeiras)
    - php artisan make:migration add_campo_to_nome-tabela_table  (cria uma migrate para atualizar um tabela)
    - php artisan migrate:status
    - php artisan migrate:fresh   (desfaz todas as migrate, e as faz novamente comeca do zero)
    - php artisan migrate:rollback   (desfaz a ultima migrate)
    - php artisan migrate:reset   (desfaz todas as migrate)
    - php artisan migrate:refresh   (desfaz todas as migrate, e as faz novamente)

#### Database - seeders
    - php artisan make:seeder NameTable
    - php artisan db:seed
    - php artisan migrate:fresh --seeder=UserSeeder
    - php artisan migrate:fresh --seed

#### Criar validacao
    - php artisan make:Request NameRequest

#### Eloquent
    - Model::all()
    - Model::save()
    - Model::findOrFail()

#### personalizar host
    - sudo nano /etc/hosts
        127.0.0.1       project.test
        ::1     ip6-project.test ip6-loopback

    - php artisan serve --host=project.test --port=80

#### tinker
    - php artisan tinker
