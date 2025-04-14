# KalliopePbx
Helper per utilizzare le RestAPI del Kalliope PBX

## Usage

To use this package for laravel, follow the steps below:

1. Install the package via Composer:
    ```bash
    composer require fbarachino/kalliopepbx:dev-main
    ```

2. Publish the config file:
    ```bash
    php artisan vendor:publish --tag=kalliopepbx-config
    ```
    and edit it with correct values.

3. Initialize and configure the library as needed (for example):
    ```php
    ...
    class RestApiCall extends Models{

        public static function getSerialNumber()
        {
            $kalliope = new KalliopePbx();
            $response = $kalliope->sendRequest('rest/dashboard/serialNumber','GET');
            return $response;
        }
       
    }
    ...
    ```
