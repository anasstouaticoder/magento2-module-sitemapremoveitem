# Mage2 Module AnassTouatiCoder SitemapRemoveItem

    ``anasstouaticoder/magento2-module-sitemapremoveitem``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Provide easy way to remove unwanted sitemap rows.

## Installation
\* = in production please use the `--keep-generated` option

### install from composer 2

 - In magento project root directory run command `composer require anasstouaticoder/magento2-module-sitemapremoveitem`
 - Enable the module by running `php bin/magento module:enable AnassTouatiCoder_SitemapRemoveItem`
 - Flush the cache by running `php bin/magento cache:flush`


### Zip file

 - Unzip the zip file in `app/code/AnassTouatiCoder`
 - Enable the module by running `php bin/magento module:enable AnassTouatiCoder_SitemapRemoveItem`
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

Go to Store => Configution => Section Atouati Tools
add Items that will be removed from SiteMaps

## Specifications

This plugin gives the administrators the ability to clean up Sitemap from unwanted URLS rows.

[see Demo](https://github.com/anasstouaticoder/magento2-module-sitemapremoveitem/wiki)

## Usage
In Back Office, go to Store => Configuration => ATOUATI TOOLS => General => URLs to remove from Sitemap.

Add unwanted uri to the table and save.
Make sure to select URI Match Type that you needed: Contains removes one or more,  equals removes one row).
- use case 1: URI => women/tops-women URI Match Type => Contains
- use case 2: URI => men/bottoms-men/pants-men.html URI Match Type => Equals
## License

[MIT](https://opensource.org/licenses/MIT)
