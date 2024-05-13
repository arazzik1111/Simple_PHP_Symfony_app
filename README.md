```markdown
# Symfony Person Management Project

This is a simple Symfony project for managing people. It allows you to create, edit, and delete people.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- PHP 7.4 or higher (Preferrably 8)
- Composer
- Symfony CLI
- XAMPP (Apache and MySQL)

### Installation

1. Clone the repository

```bash
git clone https://github.com/arazzik1111/Simple_PHP_Symfony_app.git
```

2. Navigate to the project directory

```bash
cd Simple_PHP_Symfony_app
```

3. Install dependencies

```bash
composer install
```

4. Start the XAMPP services. Ensure that Apache and MySQL are running.

5. Configure your database connection in the `.env` file. You'll find a line that looks like this:

```bash
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

Replace `db_user`, `db_password`, and `db_name` with your MySQL username, password, and the database name you want to use, respectively.

6. Start the Symfony server

```bash
symfony server:start
```

Now, you can access the application at `http://127.0.0.1:8000`.

## Usage

Navigate to `http://127.0.0.1:8000/people` to see a list of all people. From here, you can add a new person, edit an existing person, or delete a person.
```
Please replace `db_user`, `db_password`, and `db_name` with your actual database username, password, and database name. If your MySQL server is not on `127.0.0.1` or port `3306`, you'll need to update those values as well.
