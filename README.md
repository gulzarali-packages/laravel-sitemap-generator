# Laravel SiteMap Generator
Introducing the Laravel SiteMap Generator, a powerful package that effortlessly creates sitemaps for your website's URLs. With this package, you can customize the output format, set the filename, and even submit the sitemap to Google for easy crawling.

## Usage
To use the Laravel SiteMap Generator, simply import the package and initialize it with your desired URLs. You can even set the output format (text or XML), choose where to store the sitemap file (locally, publicly, or on S3), and submit the sitemap to Google for crawling.

For instance, you can create both XML and text sitemaps with a given set of URLs, submit both sitemaps to Google, and reset the filename as shown in the following code snippet:

```
use GulzarAli\SiteMap;

$siteMap = new SiteMap('https://your-domain.com'); // optional: if not provided then the APP_URL will be used
$siteMap->add('/');
$siteMap->add('/about-us');
$siteMap->add('/contact-us');

$siteMap->storage('public_root') // optional: can be local, s3, public
        ->formate('text')        // optional: can be text or xml
        ->render()               // method to store the sitemap on the specified storage
        ->submitGoogle()         // submission to google
        ->resetFileName()        // Required to reset the file name to create multiple formate sitemaps at once.
        ->formate('xml')
        ->render()
        ->submitGoogle();

```
Alternatively, you can simply create a text sitemap using the render() method. Once generated, your sitemap will be accessible at the following URLs:
```
https://your-domain.com/sitemap.xml
https://your-domain.com/sitemap.txt
```