class DigitalOceanSpacesUploader
{
    private $space;
    private $region;
    private $key;
    private $secret;
    private $storageType;

    public function __construct($space, $region, $key, $secret, $storageType)
    {
        $this->space = $space;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        $this->storageType = $storageType;
    }

    public function uploadFile($localPath, $file, $spacePath)
    {
        $date = gmdate("D, d M Y H:i:s e");
        $acl = "x-amz-acl:private";
        $contentType = "text/plain";
        $string = "PUT\n\n$contentType\n$date\n$acl\n{$this->storageType}\n/{$this->space}{$spacePath}{$file}";
        $signature = base64_encode(hash_hmac('sha1', $string, $this->secret, true));

        $url = "https://{$this->space}.{$this->region}.digitaloceanspaces.com{$spacePath}{$file}";
        $headers = [
            "Host: {$this->space}.{$this->region}.digitaloceanspaces.com",
            "Date: $date",
            "Content-Type: $contentType",
            "{$this->storageType}",
            "$acl",
            "Authorization: AWS {$this->key}:$signature"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localPath . '/' . $file));

        $fileHandle = fopen($localPath . '/' . $file, 'r');
        curl_setopt($ch, CURLOPT_INFILE, $fileHandle);

        $response = curl_exec($ch);

        fclose($fileHandle);
        curl_close($ch);

        return $response;
    }
}

// Example usage:
$SPACE = "nyc-tutorial-space";
$REGION = "nyc3";
$STORAGETYPE = "STANDARD";
$KEY = "5SGMEC00J6UPVC2A0001";
$SECRET = getenv('SECRET');

$uploader = new DigitalOceanSpacesUploader($SPACE, $REGION, $KEY, $SECRET, "x-amz-storage-class:$STORAGETYPE");

// Define the local path, file, and space path variables
$path = ".";
$spacePath = "/";
$file = "hello-world.txt";

// Run the uploadFile method
$response = $uploader->uploadFile($path, $file, $spacePath);

// Handle the response as needed
echo $response;
