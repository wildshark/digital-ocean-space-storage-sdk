# PHP Script to Upload Files to DigitalOcean Spaces

This PHP script allows you to upload files to DigitalOcean Spaces using cURL. Before using the script, make sure you have set up the required variables:

1. `$SPACE`: The name of your DigitalOcean Space. You can find it in the control panel under Settings.
2. `$REGION`: The region where your Space is located. For new Spaces, use "us-east-1"; otherwise, use the region in your endpoint (e.g., nyc3).
3. `$STORAGETYPE`: The storage type for the uploaded file, such as STANDARD, REDUCED_REDUNDANCY, etc.
4. `$KEY`: Your DigitalOcean Spaces access key.
5. `$SECRET`: Your DigitalOcean Spaces secret access key. For security reasons, it's recommended to define this as an environment variable.

## Usage

To use this script, follow these steps:

1. Make sure you have PHP installed on your server.
2. Set the required variables mentioned above with appropriate values.
3. Place the file you want to upload in the same directory as this script and update the `$file` variable with the file name.
4. Run the script.

## Code Explanation

1. The `putS3` function takes various parameters required to perform the file upload.

2. The function generates the required headers for the cURL request, including the authorization signature based on your access key and secret.

3. The function then sends a PUT request using cURL to upload the file to the specified DigitalOcean Space.

4. The script sets the local path (`$path`), file name (`$file`), and destination path in the Space (`$space_path`).

5. Finally, the script calls the `putS3` function with the specified parameters to upload the file to DigitalOcean Spaces.

Note: Make sure you have the necessary permissions and correct configurations in your DigitalOcean account to perform the upload successfully.

Please, let me know if you need any further assistance or have any questions! 

iquipe@outlook.com
