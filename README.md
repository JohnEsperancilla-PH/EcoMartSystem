└── EcoMartSystem/
    ├── bootstrap.php               # Bootstrapping the app
    ├── config.php                  # Configuration settings (e.g., database, app settings)
    ├── routes.php                  # Define routes for the system (front-end and admin routes)
    ├── Core/                       
    │   ├── App.php                 # Core application logic
    │   ├── Authenticator.php       # User authentication logic
    │   ├── Container.php           # Dependency injection container
    │   ├── Database.php            # Database connection logic
    │   ├── Response.php            # Response handling
    │   ├── Router.php              # Routing logic
    │   ├── Session.php             # Session management
    │   ├── ValidationException.php # Custom validation exception
    │   ├── Validator.php           # Form validation logic
    │   ├── functions.php           # Helper functions
    │   └── Middleware/             
    │       ├── Authenticated.php   # Auth middleware (ensures user is logged in)
    │       └── RoleMiddleware.php  # Middleware for user roles (admin, cashier)
    ├── Http/
    │   ├── Forms/                  
    │   │   └── LoginForm.php       # Login form logic
    │   ├── controllers/            
    │   │   ├── Admin/              # Admin controllers
    │   │   │   ├── dashboard.php   # Admin dashboard controller
    │   │   │   ├── users.php       # Admin user management controller
    │   │   │   └── reports.php     # Admin reports controller
    │   │   ├── Customer/           # Customer controllers
    │   │   │   ├── index.php       # Customer dashboard/controller
    │   │   │   └── checkout.php    # Customer checkout controller
    │   │   ├── products.php        # Product-related logic (listing, details)
    │   │   └── transactions.php    # Transaction controller (for sales)
    ├── Models/                      
    │   ├── Product.php             # Product model (for CRUD operations)
    │   ├── Transaction.php         # Transaction model (for sales)
    │   ├── User.php                # User model (for authentication and user data)
    │   └── Role.php                # Role model (for user roles management)
    ├── public/                     
    │   ├── index.php               # Front-end entry point
    │   └── assets/                 # Public assets (images, CSS, JS)
    │       ├── css/                # Stylesheets (e.g., Bootstrap)
    │       ├── js/                 # JavaScript files (e.g., POS logic)
    │       └── images/             # Images for the POS interface (logos, icons)
    ├── tests/                       
    │   ├── Pest.php                # Pest test framework initialization
    │   ├── TestCase.php            # Base test case class
    │   ├── Feature/                # Feature tests (e.g., user registration, transactions)
    │   │   ├── UserTest.php        # Tests related to user functionality
    │   │   └── TransactionTest.php # Tests related to transactions
    │   └── Unit/                   # Unit tests (e.g., for models or utility classes)
    │       ├── ProductTest.php     # Tests related to Product model
    │       └── ValidatorTest.php   # Tests for validation logic
    └── views/                       
        ├── 403.php                # Forbidden page view
        ├── 404.php                # Not found page view
        ├── admin/                  # Admin views
        │   ├── dashboard.view.php  # Admin dashboard view
        │   ├── users.view.php      # Admin user management view
        │   └── reports.view.php    # Admin reports view
        ├── customer/               # Customer views
        │   ├── index.view.php      # Customer dashboard view
        │   └── checkout.view.php   # Customer checkout view
        ├── products/               # Product-related views
        │   ├── list.view.php       # Product listing view
        │   └── detail.view.php     # Product details view
        ├── transactions/           # Transaction views (for sales)
        │   ├── create.view.php     # Sale creation view
        │   └── history.view.php    # Transaction history view
        ├── partials/               # Common UI components (header, footer, etc.)
        │   ├── banner.php          # Banner component
        │   ├── footer.php          # Footer component
        │   ├── head.php            # Head section for meta tags, CSS
        │   └── nav.php             # Navigation menu (for both admin and customer)
        └── forms/                  # Views for forms
            └── login.view.php      # Login form view
