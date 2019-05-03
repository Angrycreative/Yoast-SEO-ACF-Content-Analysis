## This repository is DEPRECATED
This repository is deprecated, all development is done at [https://github.com/Yoast/yoast-acf-analysis](https://github.com/Yoast/yoast-acf-analysis).
# ACF Content Analysis for Yoast SEO
This plugin ensures that Yoast SEO analysize all ACF content including FlexiContent and Repeaters

## Filters
`ysacf_exclude_fields`: exclude acf fields from Yoast scoring.


**Example:** exclude text-color field from Yoast scoring.

```
add_filter('ysacf_exclude_fields', function($fields){
    return array_merge($fields, array(
        'text_color'
    ));
}, 10);
```

`ysacf_field_overwrite`: Modify a specific field value.


**Example:** Modify a specific field value

```
add_filter('ysacf_field_overwrite', function($field_key, $field_value, $postID) {

    $data = get_field($field_key, $postID);
    
    if($data > 10) {
        $field_value = $data * 10;
    }

    return $field_value;

}, 10);
```

`ysacf_finalize`: filters the data.


**Example:** Append extra data to the final output that is send back to Yoast SEO.

```
add_filter('ysacf_finalize', function($data, $postID){
    
    $data .= 'Modify the final output e.g. append extra data or logic';
    
    return $data;
}, 10);
```