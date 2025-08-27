# BookHeaven Laravel

A modern book rental and subscription platform built with Laravel.

## Features

- **Book Catalog**: Browse thousands of books with advanced search and filtering
- **User Authentication**: Secure login and registration system
- **Shopping Cart**: Add books to cart and manage quantities
- **Subscription Plans**: Monthly and yearly subscription options
- **Audiobooks**: Stream audiobooks with built-in player
- **Admin Panel**: Comprehensive admin dashboard for managing books, users, and orders
- **Community Features**: Join book communities and interact with other readers
- **Payment Integration**: Support for multiple payment methods
- **Responsive Design**: Mobile-friendly interface with dark mode support

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd BookHeaven-Laravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   - Create a MySQL database named `bkh`
   - Update your `.env` file with database credentials
   - Run migrations:
   ```bash
   php artisan migrate
   ```

5. **Seed the database** (optional)
   ```bash
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## Project Structure

```
BookHeaven-Laravel/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Http/Middleware/     # Custom middleware
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Web routes
└── public/                 # Public assets
```

## Key Models

- **User**: User authentication and profile management
- **Book**: Book catalog with relationships to writers, genres, and categories
- **Cart**: Shopping cart functionality
- **Order**: Order management system
- **Subscription**: Subscription plan management
- **Audiobook**: Audiobook streaming features
- **Community**: Community and social features

## Routes

- `/` - Home page with featured books
- `/books/{id}` - Book details
- `/search` - Book search and filtering
- `/cart` - Shopping cart
- `/login` & `/register` - Authentication
- `/admin/*` - Admin panel (requires admin role)

## Database Schema

The application uses the existing `bkh` database schema with the following main tables:
- `users` - User accounts
- `books` - Book catalog
- `writers` - Book authors
- `genres` - Book genres
- `categories` - Book categories
- `cart` - Shopping cart items
- `orders` - Order management
- `audiobooks` - Audiobook content

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support, please contact the development team or create an issue in the repository.
