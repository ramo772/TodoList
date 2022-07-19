# TodoList

# Getting started

## Installation

Clone the repository

    git clone https://github.com/ramo772/TodoList.git

Switch to the repo folder

    cd TodoList
Install all the dependencies 

    composer install
    npm install

    
Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate
    
Watching Assets For Changes

    npm run watch
    
start the Laravel WebSocket server by issuing the artisan command:

    php artisan websockets:serve


Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000
