# Handle

A static site generator powered by PHP and the command line.

The Handle CLI takes your Markdown content and Blade theme files and generates a static site 
(HTML) for you. It's fast, flexible and powerful.

[![Build Status](https://travis-ci.org/gilbitron/Handle.svg?branch=master)](https://travis-ci.org/gilbitron/Handle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

## Requirements

* PHP v5.6+

## Install

First, download the latest version using `wget` or `curl` and extract the `phar` file and the public key.

```shell
curl -O https://raw.githubusercontent.com/gilbitron/Handle/gh-pages/handle.tar.gz
tar -zxvf handle.tar.gz
```

Then, check if it works:

```shell
php handle.phar
```

To install the `handle` command globally (instead of just using `handle.phar` in the download directory) you need to 
make the file executable and move it to somewhere in your `PATH`.

```shell
chmod +x handle.phar
sudo mv handle.phar /usr/local/bin/handle
sudo mv handle.phar.pubkey /usr/local/bin/handle.pubkey
```

Now try running `handle`.

### Updates

Updates can be done using the `handle update` command. Updates can be rolled back using the `handle rollback` command.

## Usage

### Init Command

The `init` command will create the initial file structure that you require to build a Handle site.

```shell
handle init
```

The following folders and files should now be available:

* `_cache` - Cache location. This should be writeable.
* `_content` - This is the location of the [Markdown](https://en.wikipedia.org/wiki/Markdown) files that represent the pages on your site.
* `_themes` - This contains the [Blade themes](#handle-customization-themes) that are used to generate your site HTML.
* `config.yml` - This stores your [site configuration](#handle-customization-configuration).

### Build Command

The `build` command will generate a static site (HTML files) from the Markdown files in your `_content` folder.

```shell
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
 
## File Meta
 
Each Markdown file can have a meta section above the content composed of [YAML](http://yaml.org/) data:
 
```
Title: Page Title
Template: custom
---
This is the page content...
```

Default available meta:

* `Title` - The title of the page
* `Template` - The name of the template file to use for this page (not including `.blade.php`)

The meta data is also available in your theme files as an array in the `$meta` variable.

## Customization

### Configuration

The configuration of your site can be edited by providing values in `config.yml`. The
default config values are:

* `site_title` - `Handle`
* `theme` - `default`
* `cache_path` - `_cache`
* `content_path` - `_content`
* `themes_path` - `_themes`
* `build_path`

### Themes

In Handle a theme is actually a [Blade](https://laravel.com/docs/5.1/blade) template (see the
[Blade docs](https://laravel.com/docs/5.1/blade) for more information on templating). By default the `index.blade.php`
template will be used but this can be overridden in the file meta.

The current theme can be set in `config.yml`.

The following variables are available in the theme:

* `$config` - An array of the [configuration values](#handle-customization-configuration)
* `$title` - The title of the page
* `$slug` - The slug of the page (filename without the file extension)
* `$content` - The parsed content of the page
* `$meta` - An array of the [meta values](#handle-file-meta) for the page 

## Credits

Handle was created by [Gilbert Pellegrom](http://gilbert.pellegrom.me) from
[Dev7studios](http://dev7studios.com). Released under the MIT license.