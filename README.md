# Powerstack
[![Build Status](https://travis-ci.org/powerstack/powerstack.png)](https://travis-ci.org/powerstack/powerstack)

Powerstack is a PHP 5 framework that uses a similar routing system
as Dancer (Perl web framework).

Project is still in early and active development.
The first stable release is due soon.

## Setup
Read the [Quick Start Guide] (https://github.com/powerstack/powerstack/wiki/Quick-Start)

## Examples
This is a basic route that renders a template.

    // HTTP GET request
    $app->get('/home', function($request, $params) {
        template('home.tpl');
    });

What this does is it handles any HTTP GET requests for /home
When a HTTP GET request for /home is made it executes the callback function which renders the template.

You can also define routes for HTTP POST, PUT and DELETE requests

    // HTTP POST reequest
    $app->post('/home', function($request, $params) {
        template('home.tpl');
    });

    // HTTP PUT request
    $app->put('/home', function($request, $params) {

    });

    // HTTP DELETE request
    $app->delete('/home', function($request, $params) {

    });

If you want to handle multiple HTTP request methods for a route you can

    // Handle multiple HTTP request methods
    $app->any(array('post', 'put'), '/home', function($request, $params) {
        // This function will be used for both HTTP POST and HTTP PUT requests
    });

## Contributing
All issues are tracked on Github, [Report a bug] (https://github.com/powerstack/powerstack/issues)

More info on contributing can be found on the [Wiki] (https://github.com/powerstack/powerstack/wiki/Contributing)

## Support
IRC: irc.freenode.net #powerstack

WIKI: https://github.com/powerstack/powerstack/wiki

## License
See LICENSE
