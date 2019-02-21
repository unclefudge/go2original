<?php

/*
 * Global Variables
 */

define("VALID_EMAIL_PATTERN", '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD');
define("REQUIRED_FIELD", '<span class="text-red">*</span>');

function validEmail($email)
{
    return preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $email);
}

function fieldErrorMessage($field, $errors)
{
    if ($errors->has($field))
        return '<span class="form-control-feedback">' . $errors->first($field) . '</span>';
}

function fieldHasError($field, $errors)
{
    if ($errors->has($field))
        return 'has-danger m--font-danger m--font-bolder';
}

/**
 * Generate a unique slug.
 * If it already exists, a number suffix will be appended.
 * It probably works only with MySQL.
 *
 * @link http://chrishayes.ca/blog/code/laravel-4-generating-unique-slugs-elegantly
 *
 * @param Illuminate\Database\Eloquent\Model $model
 * @param string $value
 * @return string
 */
function getUniqueSlug(\Illuminate\Database\Eloquent\Model $model, $value)
{
    $slug = \Illuminate\Support\Str::slug($value);
    if ($model->slug == $slug)
        return $slug;

    $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$model->id}'")->get());

    return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
}

function format_phone($country, $phone)
{
    $function = 'format_phone_' . $country;
    if (function_exists($function)) {
        return $function($phone);
    }

    return $phone;
}

function format_phone_au($phone)
{
    if (!isset($phone{3})) {
        return '';
    } // making sure we have something

    $stripped = preg_replace("/[^0-9]/", "", $phone);  // strip out everything but numbers
    $char2 = substr($stripped, 1, 1);
    $length = strlen($stripped);
    switch ($length) {
        case 8: // landline 9987 5423
            return preg_replace("/([0-9]{4})([0-9]{4})/", "$1 $2", $stripped);
            break;
        case 10:
            if ($char2 == '4') // mobile 0412 667 876
                return preg_replace("/([0-9]{4})([0-9]{3})([0-9]{3})/", "$1 $2 $3", $stripped);
            else               // landline (02) 9987 5423
                return preg_replace("/([0-9]{2})([0-9]{4})([0-9]{4})/", "($1) $2 $3", $stripped);
            break;
        default:
            return $phone;
            break;
    }
}

function format_phone_us($phone)
{
    if (!isset($phone{3})) {
        return '';
    }
    $stripped = preg_replace("/[^0-9]/", "", $phone);
    $length = strlen($stripped);
    switch ($length) {
        case 7:
            return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $stripped);
            break;
        case 10:
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $stripped);
            break;
        case 11:
            return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $stripped);
            break;
        default:
            return $phone;
            break;
    }
}
