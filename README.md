# 
<h1 style="text-align: center;">Magento 2 Module AnassTouatiCoder SitemapRemoveItem</h1>
<div style="text-align: center;">
  <p>Remove unwanted Sitemap items based on their URI</p>
  <img src="https://img.shields.io/badge/magento-2.2%20|%202.3%20|%202.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/anasstouaticoder/magento2-module-sitemapremoveitem" target="_blank"><img src="https://img.shields.io/packagist/v/anasstouaticoder/magento2-module-sitemapremoveitem.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/anasstouaticoder/magento2-module-sitemapremoveitem" target="_blank"><img src="https://poser.pugx.org/anasstouaticoder/magento2-module-sitemapremoveitem/downloads" alt="Composer Downloads" /></a>
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

    ``anasstouaticoder/magento2-module-sitemapremoveitem``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Usage](#markdown-header-usage)
 - [License](#markdown-header-license)


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
