# WPK Core Graphql

A plugin for [wpk-core](https://github.com/TheUnderScorer/wpk-core) framework that creates Graphql server in Wordpress. Uses awesome [graphqlite](https://github.com/thecodingmachine/graphqlite) library.

## Usage

1. Installation:
``composer require the-under-scorer/wpk-core-graphql``

-  Add graphql route in your `routes.php` file:

```php
   <?php
   
   use UnderScorer\Core\Http\Router;
   use UnderScorer\GraphqlServer\Http\Controllers\GraphqlServer;
   
   /**
    * @var Router $router
    */
   
   $router
       ->route()
       ->any()
       ->match( '/graphql' )
       ->controller( GraphqlServer::class );
```

-  Add `SchemaProvider` and `DataLoaderProvider` into your `providers.php` :
    ```php
    <?php
    
    use UnderScorer\Core\Providers\CacheProvider;
    use UnderScorer\Core\Providers\DatabaseProvider;
    use UnderScorer\Core\Providers\EnqueueProvider;
    use UnderScorer\Core\Providers\FileSystemProvider;
    use UnderScorer\Core\Providers\LoggerProvider;
    use UnderScorer\Core\Providers\NoticesProvider;
    use UnderScorer\Core\Providers\RouterProvider;
    use UnderScorer\Core\Providers\SerializerProvider;
    use UnderScorer\Core\Providers\ValidationProvider;
    use UnderScorer\Core\Providers\ViewProvider;
    use UnderScorer\GraphqlServer\Providers\SchemaProvider;
    use UnderScorer\GraphqlServer\Providers\DataLoaderProvider;
    
    return [
        CacheProvider::class,
        RouterProvider::class,
        SchemaProvider::class, // Graphql Schema Provider,
        DataLoaderProvider::class, // DataLoader Provider
        SerializerProvider::class,
        FileSystemProvider::class,
        EnqueueProvider::class,
        ViewProvider::class,
        NoticesProvider::class,
        LoggerProvider::class,
        DatabaseProvider::class,
        ValidationProvider::class,
    ];

    ```
   
-  Add path to library to composer.json at "autoload-dev.psr-4" (it is needed for Graphql Types and Controllers provided by this library) : 
```json
{
    "autoload-dev": {
        "psr-4": {
          "UnderScorer\\GraphqlServer\\": "vendor/the-under-scorer/wpk-core-graphql/src/GraphqlServer"
        }
      }
}
```

