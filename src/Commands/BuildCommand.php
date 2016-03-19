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
        'site_title' => 'Handle',
        'theme'      => 'default',
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
            if (!is_dir($path . '/themes/' . $config['theme'])) {
                throw new \Exception('The theme "/themes/' . $config['theme'] . '" does not exist');
            }

            $renderer = $this->getRenderer($path . '/themes/' . $config['theme']);

            $contentFiles = $this->getContentFiles($path . '/content');
            foreach ($contentFiles as $contentFile) {
                $content       = file_get_contents($contentFile);
                $meta          = $this->parseMeta($content);
                $parsedContent = $this->parseContent($content);

                $filename     = basename($contentFile, '.md');
                $filepath     = str_replace($path . '/content', $path . '/public', dirname($contentFile));
                $fullFilepath = $filepath . '/' . $filename . '.html';

                if (!is_dir($filepath)) {
                    mkdir($filepath, 0777, true);
                }

                $html = $renderer->render($meta['template'], [
                    'config'  => $config,
                    'title'   => $meta['title'],
                    'content' => $parsedContent,
                ]);
                file_put_contents($fullFilepath, $html);

                $output->writeln(str_replace($path . '/public', '', $fullFilepath) . ' generated...');
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
        if (file_exists($sitePath . '/config.yml')) {
            $config = file_get_contents($sitePath . '/config.yml');

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
     * @return AbstractEngineRenderer
     */
    protected function getRenderer($themePath)
    {
        return new BladeRenderer([$themePath], ['cache_path' => $themePath . '/cache']);
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
        list($meta, $contents) = preg_split('/\-{3,}/m', $content, 2);

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
        list($meta, $contents) = preg_split('/\-{3,}/m', $content, 2);

        if ($contents) {
            return Parsedown::instance()->text(trim($contents));
        }

        return $content;
    }
}