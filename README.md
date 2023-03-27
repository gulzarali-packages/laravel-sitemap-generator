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

// optional formate: can be text/xml. default = text
// optional storage object:i-e storage: local, public, s3 ... default = local
// optional file name. default = sitemap.xml
$siteMap->formate('text')
        ->storage('local')
        ->render('sitemap.xml');

or 
$siteMap->render();
```

Now you can access the sitemap from https://your-domain.com/sitemap.xml
