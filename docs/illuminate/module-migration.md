# Module Migration Notes #

This guide describes the minimum required work to migrate a MunkiReport v5 module to v6 without any significant change.

## Style Changes ##

You will notice that widgets, listings, and reports may have strange spacing.
This is due to the upgrade to Bootstrap v4.

All specific changes are mentioned in [Bootstrap: Migrating to v4](https://getbootstrap.com/docs/4.6/migration/).
Pay special attention to these items:

* Widgets need to be changed from the `panel` component into the `card` component. This will affect the whole header/body div as well.
  Mentioned in [Components](https://getbootstrap.com/docs/4.6/migration/#components).
* The `<list-link>` custom tag has to be changed into an actual link with a list icon like:

```html
  <a href="/show/listing/ard/ard" class="pull-right"><i class="fa fa-list"></i></a>
```

* Listings should use `container-fluid` instead of `container` to take 100% width at smaller sizes otherwise the data table will be squashed.
* `badge` replaces `label` for object counts
* You need to add top-padding to avoid the container slipping underneath the nav, by placing a `pt-4` class on the first `<div class="row">` in the listing.
* Modules with custom admin pages dont have a systemic approach to upgrades, just refer to the "Migrating to v4" article.

## Widget System ##

The widget system should eventually be a frontend concern, but for now, the functionality has been re-implemented using
[Blade Components](https://laravel.com/docs/9.x/blade#components).

The main dashboard is rendered in `resources/views/dashboards/default.blade.php`, and makes use of blade's dynamic
component functionality to render each widget.

* For widgets that are defined in YAML with a **type**, the type itself is a blade component template.
* If a widget hasnt been registered via provides or discovery, the blade component is the "not found" widget.
* MunkiReport v5 view-based widgets (.php) use the `widget.legacy` template but in actual fact the App\View\Components\Widget\Legacy class 
  outputs the v5 view directly without going through Blade.
