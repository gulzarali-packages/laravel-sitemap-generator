# Laravel SiteMap Generator
A laravel package to generate the google sitemaps in text and xml formates.

## Usage
import the package as:
```
use GulzarAli\SiteMap
```
initialize the package and add urls
```
// constructor app url is optional. if not provided APP_URL will be used from .env file
$siteMap = new SiteMap('https://your-domain.com');
$siteMap->add('/');
$siteMap->add('/about-us');
$siteMap->add('/contact-us');

// optional: default = text. formate can be text/xml
// optional: default = local. storage: local, public, s3 etc
// optional: file name to render method
$siteMap->formate('text')
        ->storage('local')
        ->render('sitemap.xml');

or 
$siteMap->render();
```
