<?php

$SPACE = "nyc-tutorial-space"; // Find your endpoint in the control panel, under Settings.
$REGION = "nyc3"; // Must be "us-east-1" when creating new Spaces. Otherwise, use the region in your endpoint (e.g. nyc3).
$STORAGETYPE = "STANDARD"; // Storage type, can be STANDARD, REDUCED_REDUNDANCY, etc.
$KEY = "5SGMEC00J6UPVC2A0001"; // Access key pair. You can create access key pairs using the control panel or API.
$SECRET = getenv('SECRET'); // Secret access key defined through an environment variable.

// Define a function that uploads your object via cURL.
function putS3($path, $file, $space_path, $space, $region, $key, $secret, $storage_type)
{
    $date = gmdate("D, d M Y H:i:s e");
    $acl = "x-amz-acl:private"; // Defines Access-control List (ACL) permissions, such as private or public.
    $content_type = "text/plain"; // Defines the type of content you are uploading.
    $string = "PUT\n\n$content_type\n$date\n$acl\n$storage_type\n/$space$space_path$file";
    $signature = base64_encode(hash_hmac('sha1', $string, $secret, true));

    $url = "https://$space.$region.digitaloceanspaces.com$space_path$file";
    $headers = [
        "Host: $space.$region.digitaloceanspaces.com",
        "Date: $date",
        "Content-Type: $content_type",
        "$storage_type",
        "$acl",
        "Authorization: AWS $key:$signature"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PUT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($path . '/' . $file));

    $fileHandle = fopen($path . '/' . $file, 'r');
    curl_setopt($ch, CURLOPT_INFILE, $fileHandle);

    $response = curl_exec($ch);

    fclose($fileHandle);
    curl_close($ch);

    return $response;
}

// Define the local path, file, and space path variables
$path = ".";
$space_path = "/";
$file = "hello-world.txt";

// Run the putS3 function
putS3($path, $file, $space_path, $SPACE, $REGION, $KEY, $SECRET, "x-amz-storage-class:$STORAGETYPE");

?>
