<?php

namespace fphammerle\helpers;

class Path
{
    use PropertyAccessTrait;

    /**
     * @var Path|null
     */
    protected $_dir_path;
    /**
     * @var string|null
     */
    protected $_filename;
    /**
     * @var string|null
     */
    protected $_extension;

    /**
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->path ?: '';
    }

    /**
     * @return string|null
     */
    public function getBasename()
    {
        return StringHelper::unite([
            $this->_filename,
            StringHelper::prepend('.', $this->_extension),
            ]);
    }

    /**
     * @return string|null
     */
    public function setBasename($basename)
    {
        $this->setExtension(pathinfo($basename, PATHINFO_EXTENSION));

        if(isset($this->_extension)) {
            $this->setFilename(substr(
                $basename,
                0,
                strlen($basename) - strlen($this->_extension) - 1
            ));
        } else {
            $this->setFilename($basename);
        }
    }

    /**
     * @return Path|null
     */
    public function getDirPath()
    {
        return $this->_dir_path;
    }

    /**
     * @var Path|string|null $path
     */
    public function setDirPath($path)
    {
        if($path instanceof Path) {
            $this->_dir_path = $path;
        } elseif($path === null) {
            $this->_dir_path = null;
        } else {
            $this->_dir_path = new self($path);
        }
    }

    /**
     * @return string|null
     */
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * @param string|null $extension
     */
    public function setExtension($extension)
    {
        $this->_extension = ((string)$extension) ?: null;
    }

    /**
     * @return string|null
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * @param string|null $filename
     */
    public function setFilename($filename)
    {
        $this->_filename = ((string)$filename) ?: null;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        if($this->isRoot()) {
            return '/';
        } elseif(!isset($this->_dir_path)) {
            return $this->basename;
        } else {
            $dir_path = $this->_dir_path->path;
            if($dir_path == '/') {
                return '/' . $this->basename;
            } else {
                return $dir_path . '/' . $this->basename;
            }
        }
    }

    /**
     * @return string|null
     */
    public function setPath($path)
    {
        $basename = basename($path);
        $this->setBasename($basename);
        if(strlen($basename) < strlen($path)) {
            $dirname = dirname($path);
            if($dirname == $path) { // root?
                $this->setDirPath($this);
            } else {
                $this->setDirPath(dirname($path));
            }
        } else {
            $this->setDirPath(null);
        }
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        return $this->_dir_path === $this;
    }
}
