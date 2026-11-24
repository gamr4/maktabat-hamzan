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
- Client portal: clients can browse their order history and place new orders
  through the same order form as staff, with their client account selected
  automatically
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
| GET | `/profil` | `profile.show` | Profile page for every role |
| PUT | `/profil` | `profile.update` | Update own name and email |
| PUT | `/profil/mot-de-passe` | `profile.password` | Change own password |
| GET | `/commandes/create` | `commandes.create` | New order form (clients use the same form with their account prefilled) |
| POST | `/commandes` | `commandes.store` | Create an order (clients can only create for their own account) |
| GET | `/commandes/{commande}` | `commandes.show` | Order details (clients can only view their own orders) |
| GET | `/portail/commandes` | `portail.commandes` | Client's own order history |
| GET | `/portail/commandes/{commande}` | `portail.commandes.show` | Client order details (scoped to own accounts) |
| Resource | `/clients` | `clients.*` | Clients CRUD (admin & gestionnaire) |
| Resource | `/fournisseurs` | `fournisseurs.*` | Fournisseurs CRUD (admin) |
| Resource | `/produits` | `produits.*` | Produits CRUD (admin & gestionnaire) |
| Resource | `/commandes` | `commandes.*` | Order list, edit and delete (admin & gestionnaire) |

The `commandes` resource is split: clients may only use `create`, `store` and view their own `show`; every other order action stays staff-only.

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