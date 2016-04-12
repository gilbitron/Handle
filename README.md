[![Build Status](https://travis-ci.org/gilbitron/Handle.svg?branch=master)](https://travis-ci.org/gilbitron/Handle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

# Handle

A static site generator powered by PHP and the command line.

## Requirements

* PHP v5.6+

## Install

First, download the latest version using `wget` or `curl` and extract the `phar` file and the public key. For example:

```
curl -O https://raw.githubusercontent.com/gilbitron/Handle/gh-pages/handle.tar.gz
tar -zxvf handle.tar.gz
```

Then, check if it works:

```
php handle.phar
```

To install the `handle` command globally (instead of just using `handle.phar` in the download directory) you need to 
make the file executable and move it to somewhere in your `PATH`. For example:

```
chmod +x handle.phar
sudo mv handle.phar /usr/local/bin/handle
sudo mv handle.phar.pubkey /usr/local/bin/handle.pubkey
```

Now try running `handle`.

#### Updates

Updates can be done using the `handle update` command. Updates can be rolled back using the `handle rollback` command.

## Usage

### Init Command

The `init` command will create the initial file structure that you require to build a Handle site. For example:

```
cd /var/www
handle init
```

The following folders and files should now be available:

* `_cache` - Cache location. This should be writeable.
* `_content` - This is the location of the [Markdown](https://en.wikipedia.org/wiki/Markdown) files that represent the pages on your site.
* `_themes` - This contains the [Blade](https://laravel.com/docs/5.1/blade) themes that are used to generate your site HTML.
* `.htaccess` - If you're using Apache this will strip the `.html` from your URLs.
* `config.yml` - This stores your site configuration.

### Build Command

The `build` command will generate a static site (HTML files) from the Markdown files in your `_content` folder. For example:

```
cd /var/www
handle build
```

The structure of the files in the `_content` folder will be honoured in the generated site. For example:
 
 File Location             | Site URL       
 ------------------------- | ---------------
 `_content/index.md`        | `/`            
 `_content/about.md`        | `/about`       
 `_content/work/index.md`   | `/work`        
 `_content/work/project.md` | `/work/project`
 
 Note that the `build` command also has a `--watch` option if you want to watch for file changes and trigger
 automatic builds while you are working.
 
## Customization
 
### File Meta
 
Each Markdown file can have a meta section above the content composed of [YAML](http://yaml.org/) data:
 
```
Title: Page Title
Template: custom
---
This is the page content...
```

Available meta:

* `Title` - The title of the page
* `Template` - The name of the template file to use for this page

### Themes

In Handle a theme is actually a [Blade](https://laravel.com/docs/5.1/blade) template (see the
[Blade docs](https://laravel.com/docs/5.1/blade) for more information on templating). By default the `index.blade.php`
template will be used but this can be overridden in the file meta.

The current theme can be set in `config.yml`.

## Credits

Handle was created by [Gilbert Pellegrom](http://gilbert.pellegrom.me) from
[Dev7studios](http://dev7studios.com). Released under the MIT license.