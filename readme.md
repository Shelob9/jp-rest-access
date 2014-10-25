JP REST Access
=====================
Adds common access and security filters for the WordPress REST API (WP REST).

It does the following:
1) Sets a cross-domain CORS header for the API to prevent cross-domain errors when accessing from a diffrent domain. By default it allows all domains ("*"). You can set another domain or any array of domains with the "jp_rest_access_cors" filter.
2) Allows for requests to the posts route to use the offset filter, without pagination.
3) Sets a maximum amount of posts that can be requested at once from the posts endpoint. By default the max is 20, that value can be changed with the "jp_rest_access_max_posts_per_page" filter. Prevents someone from trying to DDOS a site with a lot of posts by requesting a ton of posts_per_page.


### Installation
This is not a plugin. It's a composer library. Add `"shelob9/jp-rest-access": "dev-master"` to your site/plugin/theme's composer.json. 

### Controlling Cross-Orgin Domain (CORS) Headers
* Allow from any domain
This it the dfault behaviour

* Allow from one domain
```php
add_filter( 'jp_rest_access_cors', function( $domains ) {
	return 'http://somedomain.com';
});
```

* Allow from two domains
```php
add_filter( 'jp_rest_access_cors', function( $domains ) {
	return array( 'http://somedomain.com', 'http://another_url.com' );
});
```

### Limiting the Max Number of Posts Per Request
```php
add_filter( 'jp_rest_access_max_posts_per_page', function( $max_posts ) {
  return 5;
}```

### License
Copyright 2014 Josh Pollock. Licensed under the terms of the GNU General public license version 2. Please share with your neighbor.
