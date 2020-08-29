## Middleware package

### What this do?

> The goal for this repository is to facilitate the authentication by JWT in lumen.

### How to use
 - Add too the repository to composer.json
 ```json
    "repositories": [
        {
            "type": "vcs",
            "url": "ssh://git@github.com/paulo123araujo/middleware-lumen-auth.git"
        }
    ]
 ```
 - Add the package as a requirement to the composer.json manually or via command
 ```bash
    composer require paulo123araujo/middleware-lumen-auth
 ```
 - Add the middlewares to bootstrap/app.php the following lines:
 ```php
    $app->routeMiddleware([
        'authenticate' => Middlewares\Authenticate::class,
        ....
    ]);
 ```

 - Copy the config/jwt.php to the Lumen project that you want to use the middlewares
