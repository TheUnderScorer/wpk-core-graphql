# WPK Core Graphql

A plugin for [wpk-core](https://github.com/TheUnderScorer/wpk-core) framework that creates Graphql server in Wordpress. Uses awesome [graphqlite](https://github.com/thecodingmachine/graphqlite) library.

## Usage

1. Installation:
``composer require the-under-scorer/wpk-core-graphql``

2. Add graphql route in your `routes.php` file:

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

3. Add `SchemaProvider` into your `providers.php` :
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
    
    return [
        CacheProvider::class,
        RouterProvider::class,
        SchemaProvider::class, // Graphql Schema Provider
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

