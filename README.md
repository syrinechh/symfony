# Symfony Project

This is a Symfony project.

## Requirements
- PHP 7.1.3 or higher
- Composer

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/syrinechh/symfony.git
   cd symfony
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up your environment variables:
   ```bash
   cp .env.example .env
   ```
4. Run migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

## Usage

To run the application:
```bash
symfony serve
```

## License

This project is licensed under the MIT License.