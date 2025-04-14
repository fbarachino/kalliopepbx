# KalliopePbx
Package per Laravel per utilizzare le RestAPI del Kalliope PBX

## Usage

To use this package for laravel, follow the steps below:

1. Install the package via Composer:
    ```bash
    composer require fbarachino/kalliopepbx
    ```

2. Publish the config file:
    ```bash
    php artisan vendor:publish --tag=kalliopepbx-config
    ```
    and edit it with correct values.

3. Initialize and configure the library as needed (for example):
    ```php
    ...
    use Illuminate\Support\Facades\Storage;
    use fbarachino\kalliopepbx\KalliopePbx;
    
    class RestApiCall {

        public static function getSerialNumber()
        {
            $kalliope = new KalliopePbx();
            $response = $kalliope->sendRequest('rest/dashboard/serialNumber','GET');
            return $response;
        }
       
       // remeber to execute 'php artisan storage:link'
        public static function backup($filename, $description)
        {
            $kalliope = new KalliopePbx();
            $firmware = $kalliope->sendRequest('/rest/dashboard/firmwareVersion','GET');
            $data = 
            [
                'backup'=>
                [
                    'filename' => $filename,
                    'comment' => $description
                ],
            ];

            $response = json_decode($kalliope->sendRequest('rest/backup/create/'.$firmware,'POST',$data);
            return Storage::disk('local')->put('/public/backup/kalliope/'.$filename.'.bak', $response);         
        } 

    }
    ...
    ```
4.  You can use the helper as:
    ```php
    $kalliope->sendRequest($url,$method,$data);
    ```
    Where ```$url``` is the URL of the API request,
    ```$method``` is the method (GET,POST,PUT,PATCH,DELETE), and ```$data``` is the array of fields.
    
