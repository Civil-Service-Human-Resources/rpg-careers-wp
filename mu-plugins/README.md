# RPG Must use plugins / extensions 

## RPG Careers WP Utils Plugin

Plugin providing various helper functions to support the CMS - sits in mu-plugins folder so automatically run.


## RPG Careers WP Snippet Plugin

Plugin to allow for management of bespoke HTML snippets.  Snippet can be created in WP admin and then a shortcode is used on pages to pull that through:

```
[rpg_snippet tagcode="406"]
```

## RPG Careers WP One Time Plugin

Plugin that is run only once before it is removed from disk

## RPG Careers WP Restrict Login Plugin

Plugin that handles failed logins to the WordPress admin pages - when limit reached user is locked out

## RPG Careers WP Non Auth Preview Plugin

Plugin that provides the ability for non-authenticated users to view draft pages without the need to login to the WordPress admin pages

## Notes
php files are deployed to mu-plugins folder under wp-content.  Becomes a must use plugin which cannot be altered in anyway in the WP backend.
