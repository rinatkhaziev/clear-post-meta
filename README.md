## Clear Post Meta
Have you ever wished you could delete all post meta for the post/page/custom post type you're editing with 1 click? Well now you can!

### How to use
* Activate the plugin.
* Navigate to a post edit screen.
* You will see admin bar item (and a meta box, for good measure).
* Click `Clear Post Meta` and confirm that you really want to do it.

### Notes
By default editors and above can delete meta, to change that you can use `cpm_clear_cap` filter:
```php
add_filter( 'cpm_clear_cap', function() { return 'manage_options'; } );
```

### Requirements
This plugin requires PHP7. I repeat, _this plugin requires PHP7_.

### Changelog
* 0.1 Initial Release