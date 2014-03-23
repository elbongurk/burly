<?php

namespace Burly;

class Loader implements \Handlebars\Loader
{
    private $_baseDir;
    private $_extension = '.handlebars';
    private $_prefix = '';
    private $_templates = array();
    private $_wp_filesystem;

    public function __construct($baseDirs, array $options = array())
    {
        if (is_string($baseDirs)) {
            $baseDirs = array(rtrim(realpath($baseDirs), '/'));
        } else {
            foreach ($baseDirs as &$dir) {
                $dir = rtrim(realpath($dir), '/');
            }
            unset($dir);
        }

        $this->_baseDir = $baseDirs;

        foreach ($this->_baseDir as $dir) {
            if (!is_dir($dir)) {
                throw new \RuntimeException(
                    'FilesystemLoader baseDir must be a directory: ' . $dir
                );
            }
        }

        if (isset($options['extension'])) {
            $this->_extension = '.' . ltrim($options['extension'], '.');
        }

        if (isset($options['prefix'])) {
            $this->_prefix = $options['prefix'];
        }
        
        $this->_wp_filesystem = new \WP_Filesystem_Direct(array());
	}

    public function load($name)
    {
        if (!isset($this->_templates[$name])) {
            $this->_templates[$name] = $this->loadFile($name);
        }

        return new \Handlebars\String($this->_templates[$name]);
    }

    protected function loadFile($name)
    {
        $fileName = $this->getFileName($name);

        if ($fileName === false) {
            throw new \InvalidArgumentException('Template ' . $name . ' not found.');
        }

        return $this->_wp_filesystem->get_contents($fileName);
    }

    protected function getFileName($name)
    {
        foreach ($this->_baseDir as $baseDir) {
            $fileName = $baseDir . '/';
            $fileParts = explode('/', $name);
            $file = array_pop($fileParts);

            if (substr($file, strlen($this->_prefix)) !== $this->_prefix) {
                $file = $this->_prefix . $file;
            }

            $fileParts[] = $file;
            $fileName .= implode('/', $fileParts);
            $lastCharacters = substr($fileName, 0 - strlen($this->_extension));

            if ($lastCharacters !== $this->_extension) {
                $fileName .= $this->_extension;
            }
            if (file_exists($fileName)) {
                return $fileName;
            }
        }

        return false;
    }

}