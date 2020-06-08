# SIS generic oauth app

[![Build Status](https://img.shields.io/travis/moe-lk/auth_app/master.svg?style=flat-square)](https://travis-ci.org/moe-lk/auth_app)
[![Quality Score](https://img.shields.io/scrutinizer/g/moe-lk/auth_app.svg?style=flat-square)](https://scrutinizer-ci.com/g/moe-lk/auth_app)


This is a Generic Oauth application to facilitate authenticate SIS users to any application in relates to it by SSO 

## Installation

You can install the package via git:

```bash
git clone https://github.com/moe-lk/auth_server
cd auth_server
composer install
```
setup the Database

in rename the .env.example in to .env and change the database variables into your own

Run in the command line  
```bash 
php artisan migrate
```

then run the following command to create a new client app 

```bash
php artisan passport:client
 Which user ID should the client be assigned to?:
 > 

 What should we name the client?:
 > Grafana

 Where should we redirect the request after authorization? [http://localhost:3001/auth/callback]:
 > http://localhost:3000/login/generic_oauth // link of the client’scallback

```


## Usage
We tested this with Grafana Client and find the  configuration for the given client

``` conf
[auth.generic_oauth]
enabled = true
name = SIS
client_id = 3
client_secret = 33GZBFGSZKvUvGuK79wxcJ5CxZt4Sjn3ygfJhrLS
scopes = '*'
;email_attribute_name = email:primary
email_attribute_path = email
auth_url = http://localhost:8000/oauth/authorize #to redirect the auth_app
token_url = http://localhost:8000/oauth/token #to get the token
api_url = http://localhost:8000/api/user #to get user information
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email nizarucsc@gmail.com instead of using the issue tracker.

## Credits

- [Mohamed Nizar](https://github.com/lsflk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
