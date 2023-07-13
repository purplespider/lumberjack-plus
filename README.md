# Lumberjack Plus - Extras for the Silverstripe Lumberjack module

Adds a few extra features to the Lumberjack module.

## Funcationality
### 1. Ability to make the Lumberjack tab the default tab for the page
To make the tab always first:
```php
function getLumberjackTabPosition()
{
    return "first";
}
```
To make the tab appear first, unless the page is new and the GridField is empty:
```php
function getLumberjackTabPosition()
{
    return "first-unless-new";
}
```

### 2. Incorporates `LumberjackSortAndSummaryExtension` from [evanshunt](https://github.com/evanshunt/lumberjack-sort-and-summary).
This enables `$plural_name`, `$summary_fields`, and `$default_sort` when there is jsut a single type of child page. See [docs](https://github.com/evanshunt/lumberjack-sort-and-summary).

## Installation

```sh
composer require purplespider/lumberjack-plus
```

## Documentation

### To enable:
Add the `LumberJackplus` extension INSTEAD of `Lumberjack`, e.g.

```php
private static $extensions = [
    LumberJackplus::class,
];
```

Or, via `config.yml``:
```yml
ExampleSite\NewsHolder:
  extensions:
    - PurpleSpider\LumberjackPlus\LumberjackPlus
```

Or, to use for ALL Lumberjack instances in your project, use Injector in your site's `config.yml`:
```yml
SilverStripe\Core\Injector\Injector:
  SilverStripe\Lumberjack\Model\Lumberjack:
    class: PurpleSpider\LumberjackPlus\LumberjackPlus
```