<?php

function resolveAttrToXPath(string $attribute): string
{
    return "//*[@" . $attribute . "]";
}

function createXPathFromAttrList(): string
{
    // Loop over attributes in the constants
    // Create an xpath expression to include all of them with string concat
    $attributes = config('constants.attributes');
    $xPathString = '//*[';
    foreach ($attributes as $attribute) {
        $xPathString = $xPathString . "@" . $attribute;
        if (array_search($attribute, $attributes) === count($attributes)-1) {
            $xPathString = $xPathString . "]";
        } else {
            $xPathString = $xPathString . " or ";
        }
    }
    return $xPathString;
}