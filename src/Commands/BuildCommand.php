<?php

namespace Handle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser as YamlParser;
use Parsedown;
use Windwalker\Renderer\BladeRenderer;

class BuildCommand extends Command
{
    protected $configDefaults = [
        'site_title'   => 'Handle',
        'theme'        => 'default',
        'cache_path'   => '_cache',
        'content_path' => '_content',
        'themes_path'  => '_themes',
        'build_path'   => '',
    ];

    protected $metaDefaults = [
        'title'    => '',
        'template' => 'index',
    ];

    protected function configure()
    {
        $this->setName('build')
             ->setDescription('Build your Handle site by generating the static output')
             ->addOption('path', null, InputOption::VALUE_REQUIRED, 'Path to your Handle site');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        if (!$path || $path == '.') {
            $path = getcwd();
        }

        try {
            $config = $this->getConfig($path);

            $config['cache_path']   = $this->prepPath($config['cache_path'], $path . DIRECTORY_SEPARATOR . '_cache', 'cache');
            $config['content_path'] = $this->prepPath($config['content_path'], $path . DIRECTORY_SEPARATOR . '_content', 'content');
            $config['themes_path']  = $this->prepPath($config['themes_path'], $path . DIRECTORY_SEPARATOR . '_themes', 'themes');
            $config['build_path']   = $this->prepPath($config['build_path'], $path, 'build');

            $themePath = $config['themes_path'] . DIRECTORY_SEPARATOR . $config['theme'];
            if (!is_dir($themePath)) {
                throw new \Exception('The theme "' . $themePath . '" does not exist');
            }

            $renderer = $this->getRenderer($themePath, $config['cache_path']);

            $output->writeln('Cleaning...');
            $this->cleanBuiltContent($config['build_path'], $output);

            $output->writeln('Building...');
            $contentFiles = $this->getContentFiles($config['content_path']);
            foreach ($contentFiles as $contentFile) {
                $content       = file_get_contents($contentFile);
                $meta          = $this->parseMeta($content);
                $parsedContent = $this->parseContent($content);

                $filename     = basename($contentFile, '.md');
                $filepath     = str_replace($config['content_path'], $config['build_path'], dirname($contentFile));
                $fullFilepath = $filepath . DIRECTORY_SEPARATOR . $filename . '.html';

                if (!is_dir($filepath)) {
                    mkdir($filepath, 0777, true);
                }

                $html = $renderer->render($meta['template'], [
                    'config'  => $config,
                    'title'   => $meta['title'],
                    'content' => $parsedContent,
                ]);
                file_put_contents($fullFilepath, $html);

                $output->writeln(str_replace($config['build_path'], '', $fullFilepath) . ' generated...');
            }

            $output->writeln('<info>Finished building site</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * Get the site config
     *
     * @param string $sitePath
     * @return array
     */
    protected function getConfig($sitePath)
    {
        if (file_exists($sitePath . DIRECTORY_SEPARATOR . 'config.yml')) {
            $config = file_get_contents($sitePath . DIRECTORY_SEPARATOR . 'config.yml');

            $yaml         = new YamlParser();
            $parsedConfig = $yaml->parse($config);

            if (is_array($parsedConfig) && !empty($parsedConfig)) {
                $parsedConfig = array_merge($this->configDefaults, $parsedConfig);
            } else {
                $parsedConfig = $this->configDefaults;
            }

            return $parsedConfig;
        }

        return $this->configDefaults;
    }

    /**
     * Get the renderer
     *
     * @param string $themePath
     * @param string $cachePath
     * @return AbstractEngineRenderer
     */
    protected function getRenderer($themePath, $cachePath)
    {
        return new BladeRenderer([$themePath], ['cache_path' => $cachePath]);
    }

    /**
     * Clean all previously built files
     *
     * @param string $buildPath
     */
    protected function cleanBuiltContent($buildPath, OutputInterface $output)
    {
        if (!is_dir($buildPath)) {
            return;
        }

        $rdi = new \RecursiveDirectoryIterator($buildPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $rii = new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($rii as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getExtension() == 'html') {
                $output->writeln('Removing file: ' . str_replace($buildPath, '', $fileInfo->getRealPath()) . '...');
                unlink($fileInfo->getRealPath());
            }
        }
    }

    /**
     * Get all of the valid files from the contents directory
     *
     * @param string $contentPath
     * @return array
     */
    protected function getContentFiles($contentPath)
    {
        $contentFiles = [];
        $rdi          = new \RecursiveDirectoryIterator($contentPath);
        $rii          = new \RecursiveIteratorIterator($rdi);
        foreach ($rii as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getExtension() == 'md') {
                $contentFiles[] = $fileInfo->getPathname();
            }
        }

        return $contentFiles;
    }

    /**
     * Parse the file meta from the content
     *
     * @param string $content
     * @return array
     */
    protected function parseMeta($content)
    {
        $parts = preg_split('/\-{3,}/m', $content, 2);
        $meta  = isset($parts[0]) ? $parts[0] : '';

        $yaml       = new YamlParser();
        $parsedMeta = $yaml->parse($meta);

        if (is_array($parsedMeta)) {
            $parsedMeta = array_merge($this->metaDefaults, $parsedMeta);
        } else {
            $parsedMeta = $this->metaDefaults;
        }

        $parsedMeta = array_change_key_case($parsedMeta, CASE_LOWER);

        return $parsedMeta;
    }

    /**
     * Parse the content
     *
     * @param string $content
     * @return string
     */
    protected function parseContent($content)
    {
        $parts    = preg_split('/\-{3,}/m', $content, 2);
        $contents = isset($parts[1]) ? $parts[1] : '';

        if ($contents) {
            return Parsedown::instance()->text(trim($contents));
        }

        return $content;
    }

    /**
     * Prep a path to make sure it exists and is absolute
     *
     * @param string $path
     * @param string $default
     * @param string $name
     * @return string
     * @throws \Exception
     */
    private function prepPath($path, $default, $name)
    {
        $path = realpath($path);
        if (!$path) {
            $path = $default;
        }
        if (!realpath($path)) {
            throw new \Exception('The path to the ' . $name . ' directory does not exist: ' . $path);
        }

        return $path;
    }
}