# ACF Content Analysis for Yoast SEO
This plugin ensures that Yoast SEO analysize all ACF content including FlexiContent and Repeaters

## Filters
`ysacf_exclude_fields`: exclude acf fields from Yoast scoring.


Example: exclude text-color field from Yoast scoring.

```
add_filter('ysacf_exclude_fields', function(){
    return array(
        'text_color',
    );
});
```
