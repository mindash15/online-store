<?php
declare(strict_types=1);


function sanitizeInput(string $input): string
{
    return trim(htmlspecialchars($input));
}



function validateBrand(string $brand)
{
    $errors = [];
    if(empty($brand))
    {
        $errors[] = "Brand is required";
    }else if(!preg_match('/^[\p{L}\p{N}.,\'"!@#$%^&*()_+{}|:"<>?;\'\[\]\/ -]{1,100}$/u', $brand))
    {
        $erors = "Brand should contain letters, numbers, spaces and some punctuation symbols also should be 1 to 100 characters long.";
    }
    return $errors;
}
function validateModel(string $model)
{
    $errors = [];
    if(empty($model))
    {
        $errors[] = "Model is required";
    }else if(!preg_match('/^[\p{L}\p{N}.,\'"!@#$%^&*()_+{}|:"<>?;\'\[\]\/ -]{1,100}$/u', $model))
    {
        $erors = "Model should contain letters, numbers, spaces and some punctuation symbols also should be 1 to 100 characters long.";
    }
    return $errors;
}
function validateMaterial(string $material)
{
    $errors = [];
    if(empty($material))
    {
        $errors[] = "Material is required";
    }else if(!preg_match('/^[\p{L}\p{N}.,\'"!@#$%^&*()_+{}|:"<>?;\'\[\]\/ -]{1,100}$/u', $material))
    {
        $erors = "Material should contain letters, numbers, spaces and some punctuation symbols also should be 1 to 100 characters long.";
    }
    return $errors;
}

function validateColor(string $color)
{
    $errors = [];
    if(empty($color))
    {
        $errors[] = "Color is required";
    }else if(!preg_match('/^[\p{L}\p{N}.,\'"!@#$%^&*()_+{}|:"<>?;\'\[\]\/ -]{1,100}$/u', $color))
    {
        $erors = "Color should contain letters, numbers, spaces and some punctuation symbols also should be 1 to 100 characters long.";
    }
    return $errors;
}
function validateCategory(string $category)
{
    $errors = [];
    if(empty($category))
    {
        $errors[] = "Category is required";
    }else if(!preg_match('/^[\p{L}\p{N}.,\'"!@#$%^&*()_+{}|:"<>?;\'\[\]\/ -]{1,100}$/u', $category))
    {
        $erors = "Category should contain letters, numbers, spaces and some punctuation symbols also should be 1 to 100 characters long.";
    }
    return $errors;
}


function validatePrice(string $price){
    $errors =[];
    if(empty($price)){
        $errors[]="Price is required";

    }else if(!preg_match('/^\d+(\.\d{1,2})?$/', $price))
    {
        $errors[] = "Price should be a number and can contain up to 2 decimal points";
    }
    return $errors;

}
function validateSize(string $size){
    $errors =[];
    if(empty($size)){
        $errors[]="Size is required";

    }else if(!preg_match('/^\d+(\.\d{1,2})?$/', $size))
    {
        $errors[] = "Size should be a number and can contain up to 2 decimal points";
    }
    return $errors;

}

function handleUploadImage($image, &$errors)
{
    $allowedTypes = ['image/png', 'image/jpeg', 'image.jpg', 'image/webp'];
    $maxSize = 1024 * 1024 * 5;

    if(!in_array($image['type'],$allowedTypes)){
        $errors[] = "Invalid file type. Only PNG, JPEG, and JPG images are allowed.";
        return false;
    }
    if($image['size'] >$maxSize)
    {
        $errors[] = "Image size exceeds 5MB. Please upload an image less than 5MB.";
        return false;

    }

    $imageName = sanitizeInput($image['name']);
    $imageTmpName = $image['tmp_name'];
    $imageDir = '../uploads/';
    $ext = pathinfo($imageName, PATHINFO_EXTENSION);
    $imageName = md5(uniqid()) . '.' . $ext;//random string for naming
    $imagePath = $imageDir . $imageName;//concatenate for path its direction and name

    if (!move_uploaded_file($imageTmpName, $imagePath)) {
        $errors[] = "Error uploading image. Please try again.";
        return false;
    }

    return $imageName;

}





?>
