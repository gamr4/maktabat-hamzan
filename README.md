# Papeterie: Gestion des commandes

A Laravel web application to manage a stationery business: clients, suppliers, products and orders, with authentication and a dashboard.

## Features

- Authentication (login / logout)
- Public landing page with the Neumorphic Tech Schematic design
- Dashboard with statistics (clients, fournisseurs, produits, commandes) and the latest orders
- Full CRUD for:
  - **Clients**
  - **Fournisseurs**
  - **Produits**
  - **Commandes**
- Auto-generated codes for records (e.g. `Cli-000001`) through the `HasCode` trait
- Success messages and validation errors displayed in the main layout
- Responsive sidebar layout with Tabler Icons

## Tech Stack

- PHP 8.2+ / Laravel (11 or 12)
- Blade templates
- MySQL (or any database supported by Laravel)
- Tabler Icons (CDN) and plain CSS/JS served from `public/` — no asset build required
- Shared design tokens in `public/css/base.css`, application styles in `public/css/theme.css`, landing styles in `public/css/landing.css`

## Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd <project-folder>

# 2. Install dependencies
composer install

# 3. Configure the environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

```bash
# 4. Run migrations (and seeders if available)
php artisan migrate --seed

# 5. Start the development server
php artisan serve
```

Open http://127.0.0.1:8000 and log in.

## Routes

All routes except login are protected by the `auth` middleware.

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | `home` | Public landing page (redirects signed-in users to the dashboard) |
| GET | `/login` | `login` | Login form (guests only) |
| POST | `/login` | none | Authenticate |
| POST | `/logout` | `logout` | Log out |
| GET | `/dashboard` | `dashboard` | Dashboard |
| Resource | `/clients` | `clients.*` | Clients CRUD |
| Resource | `/fournisseurs` | `fournisseurs.*` | Fournisseurs CRUD |
| Resource | `/produits` | `produits.*` | Produits CRUD |
| Resource | `/commandes` | `commandes.*` | Commandes CRUD |

Each resource provides: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.

Run `php artisan route:list` to see everything.

## Project Structure

```
app/
├── Http/Controllers/     # Auth, Dashboard, Client, Fournisseur, Produit, Commande
└── Models/
    └── Concerns/HasCode.php   # Auto-generates codes like Cli-000001
resources/views/
├── layouts/app.blade.php      # Main layout (sidebar, alerts)
├── layouts/public.blade.php   # Public site layout (landing header/footer)
├── landing.blade.php          # Public landing page
├── dashboard.blade.php
├── clients/                   # index, create, edit, show
├── fournisseurs/
├── produits/
└── commandes/
routes/web.php
config/papeterie.php          # Client type labels
resources/views/components/   # Reusable form, table and state components
public/css/base.css           # Design tokens and shared primitives
public/css/theme.css          # Application chrome and authentication
public/css/landing.css        # Neumorphic landing styles
public/js/app.js              # Confirmation dialogs + order line editor
public/js/landing.js          # Landing navigation + reveal animations
```

## Code Generation (`HasCode`)

Models using the `HasCode` trait get an auto-incremented string primary key with a prefix:

```php
use App\Models\Concerns\HasCode;

class Client extends Model
{
    use HasCode;

    protected static string $codePrefix = 'Cli';
    protected $primaryKey = 'code_client';
    protected $keyType = 'string';
    public $incrementing = false;
}
```

Result: `Cli-000001`, `Cli-000002`, ...


## Author

Laravelers Team