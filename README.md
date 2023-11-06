# Kanban Project with Laravel (KayTask)

This is a Kanban project (KayTask) written in Laravel. You can use it by following these simple steps:

## Installation

1. **Clone the Project**: First, clone the project to your computer using your preferred Git client or the command line:

    ```bash
    git clone https://github.com/jameskaydev/kanban.git ```
    ```

2. **Install Dependencies**: Navigate to the project directory and run the following command to install the necessary dependencies using Composer:

   ```bash
   composer install
   ```
   
3. **Environment Variables**:  Copy the .env.example file to a new .env file. You can do this manually or use the following command:

   ```bash
   cp .env.example .env
   ```

4. **Database Configuration**: Open the .env file and provide your database connection details. Ensure the DB_DATABASE, DB_USERNAME, and DB_PASSWORD variables are correctly set.

5. **Create Database Tables**: To set up the required database tables and import some test data, run the following command:
   ```bash
    php artisan migrate:fresh --seed
   ```

## Usage
You are now ready to use the GitHub Kanban Project with Laravel. Here's some login information to get started:

- **Login with Usernames** : You can log in with any of the following usernames:
  - jameskay1@test.com
  - jameskay2@test.com
  - jameskay3@test.com
- **Password** : Use the password "12345678" for all accounts.
  
  

