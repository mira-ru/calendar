# Название приложения
set :application, "Calendar"

# Путь к репозиторию для сервера, на который происходит деплой
set :repository,  "git@github.com:mira-ru/calendar.git"

# Путь к репозиторию от локальной машины, с которой запускаем деплой
set :local_repository, "git@github.com:mira-ru/calendar.git"

# Тип хранилища
set :scm, :git

# Директория на удаленном сервере, в которую производим деплой
set :deploy_to, "/home/calendar/www/"

# Сервер, на который деплоим (с указанием ролей для сервера)
server "miracentr.ru", :web, :app, :db, :primary => true

# Пользователь на сервере, под которым производится деплой
set :user, "calendar"
set :password, "1"
set :port,  "7021"

# Запрет запуска команд под sudo на удаленном сервере
set :use_sudo, false

# Протокол соединения с удаленным сервером
set :via, "scp"

# Ветка в хранилище, с которой производится выгрузка
set :branch, "master"

# Выгрузка только изменений с момента последнего релиза (без полной перезаливки)
set :deploy_via, :remote_cache

# Исключение некоторых файлов и директорий из релиза
set :copy_exclude, [".git/", ".svn/", ".DS_Store", ".gitignore", ".htaccess", "Capfile", "/config/", "build.xml", "/html/", "/protected/runtime/", "/index-test.php"]

# Выполнение миграций, очистка кешей и перезапуск воркеров
after "deploy", "deploy:migration"

# Чистка старых релизов (оставляем последние 5)
after "deploy", "deploy:cleanup"

# Переопределение стандартных методов, использующихся для RoR приложений
namespace :deploy do
  [:start, :stop, :restart].each do |task_name|
    task task_name do
      # nothing
    end
    
    # Миграция БД
    task :migration do
      run "php #{latest_release}/protected/yiic migrate --interactive=0"
    end
  end


  # Настройка приложения для работы
  task :finalize_update, :except => { :no_release => true } do

    # Создание символьной ссылки на конфигурационные файлы веб и консольного запуска
    run <<-CMD
	           cp #{shared_path}/protected/config/production.php #{latest_release}/protected/config/production.php
	           & cp #{shared_path}/protected/config/console.php #{latest_release}/protected/config/console.php
    CMD

    # создание runtime, assets и generated (для генерации less)
    run <<-CMD
           ln -s #{shared_path}/protected/runtime #{latest_release}/protected/runtime &
           rm -rf #{shared_path}/assets/* &
           rm -f #{shared_path}/protected/runtime/timeFile.dat
    CMD

    # Перевод приложения в режим Production
    run <<-CMD
                rm #{latest_release}/index.php
    CMD

    run <<-CMD
                mv #{latest_release}/index-prod.php #{latest_release}/index.php
    CMD

    # Создание seo-файлов
    #run <<-CMD
    #            ln -s #{shared_path}/webroot/robots.txt #{latest_release}/robots.txt &
    #            ln -s #{shared_path}/webroot/sitemap.xml #{latest_release}/sitemap.xml &
    #            ln -s #{shared_path}/webroot/sitemaps #{latest_release}/sitemaps &
    #            ln -s #{shared_path}/webroot/cmsmagazine3ea0e0ccbabc822699815533afce40ae.txt #{latest_release}/cmsmagazine3ea0e0ccbabc822699815533afce40ae.txt &
    #            ln -s #{shared_path}/webroot/wmail_a252536a7b6804d3.html #{latest_release}/wmail_a252536a7b6804d3.html
    #CMD

  end

end



#role :web, "your web-server here"                          # Your HTTP server, Apache/etc
#role :app, "your app-server here"                          # This may be the same as your `Web` server
#role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
#role :db,  "your slave db-server here"

# namespace :deploy do
#   task :start {}
#   task :stop {}
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
