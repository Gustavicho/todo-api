# Symfony ToDo List API

This is a Symfony project, using version 7.1, developed as part of study to learn a new framework.
The project makes use of dependency injection, PHP attributes, and includes entity management such as `Task` and `ToDoList`.

## Table of Contents

1. [Technologies Used](#technologies-used)
2. [Installation](#installation)
3. [Usage](#usage)
   - [Important Routes](#important-routes)
4. [Project Structure](#project-structure)
5. [Features](#features)
6. [License](#license)

## Technologies Used

- **Docker**
- **Symfony 7.1**
- **PHP 8.2** or higher
- **MySQL** (database)
- **PHP-CS-Fixer** (for code standardization)
- **Composer** (dependency manager)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/your-username/your-project.git
   cd your-project
   ```

2. **Optional Steps (if not using Docker): The following steps are only necessary if you choose not to use Docker and want to configure the app locally.**

3. **Install dependencies:**

   ```bash
   composer install
   ```

4. **Configure the \*\***`.env`\***\* file:**

   Duplicate the `.env.example` file to `.env` and configure the environment variables:

   ```bash
   cp .env.example .env
   ```

   Adjust the database settings:

   ```bash
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/your_database"
   ```

5. **Generate encryption keys:**

   ```bash
   symfony console secrets:generate-keys
   ```

6. **Run database migrations:**

   ```bash
   symfony console doctrine:migrations:migrate
   ```

## Usage

### Development Server

To run the application locally:

- **With Docker**: Simply run:

  ```bash
  docker compose up -d --build
  ```

  This will automatically start the app and expose it on port `8080`.

- **Without Docker**: If you don't have Docker installed, you will need to configure the environment and run the application using Symfony's built-in server:

  ```bash
  php bin/console server:start
  ```

  Or, if you're using Symfony CLI:

  ```bash
  symfony server:start
  ```

  This will expose the app on port `8000` by default.

### Important Routes

#### Lists

- `GET /api/lists`\
  Retrieves all task lists.

- `GET /api/lists/{id}`\
  Retrieves a specific task list by its ID.

- `POST /api/lists`\
  Creates a new task list. Requires a JSON payload with the task list data.

- `PUT /api/lists/{id}`\
  Updates an existing task list by its ID. Requires a JSON payload with the updated data.

- `DELETE /api/lists/{id}`\
  Deletes a specific task list by its ID.

#### Tasks

- `GET /api/lists/{id}/tasks`\
  Retrieves all tasks associated with a specific task list.

- `GET /api/lists/{id}/tasks/{taskId}`\
  Retrieves a specific task from a task list by its `taskId`.

- `POST /api/lists/{id}/tasks`\
  Adds a new task to a specific task list. Requires a JSON payload with the task data.

- `PUT /api/lists/{id}/tasks/{taskId}`\
  Updates a specific task in a task list. Requires a JSON payload with the updated task data.

- `DELETE /api/lists/{id}/tasks/{taskId}`\
  Deletes a specific task from a task list by its `taskId`.

Access the application in your browser: `http://127.0.0.1:8080` (if using Docker) or `http://127.0.0.1:8000` (if not using Docker).

## Project Structure

- **src/Entity**: Contains the project's entities.
- **src/Trait**: Contains traits for simplify entities.
- **src/Repository**: Contains the repositories for database interaction.
- **src/Interface**: Contains the interface for OOP
- **src/Controller**: Contains the controllers responsible for routes and business logic.
- **config/**: Symfony configuration files.
- **docker/**: Contains Dockerfiles for the project.

## Features

- [x] Task management (`Task`).
- [x] Task lists (`ToDoList`).
- [ ] User auth
- [ ] User roles
- [x] Dockerize

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.txt) file for details.
