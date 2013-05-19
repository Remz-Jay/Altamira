<?php
/**
 * Class definition for \Altamira\FilesRenderer
 * @author relwell
 *
 */
namespace Altamira;

/**
 * Used for rendering JavaScript files
 */
class FilesRenderer extends \ArrayIterator
{
    /**
     * Constructor method. Providing a path prepends that path to all files.
     * @param array $array
     */
    public function __construct(array $array)
    {
        $mutatedVals = array();
        foreach ($array as $file) {
            $mutatedVals[] = $this->handleMinify($file);
        }

        parent::__construct($mutatedVals);
    }

    /**
     * Render method. Sends to output buffer.
     * @return \Altamira\FilesRenderer provides fluent interface
     */
    public function render()
    {
        echo "<script type=\"text/javascript\" src=\"". $this->current() . "\"></script>\n";

        return $this;

    }

    /**
     * Hooks into ArrayIterator::append() to make sure the file passed includes the .min extension if required
     *
     * @param string $val
     *
     * @see ArrayIterator::append()
     *
     * @return bool
     */
    public function append($val)
    {
        parent::append($this->handleMinify($val));

        return true;
    }

    /**
     * Ensures the value contains the minification extension upon set, if required
     * @param int    $offset
     * @param string $val
     *
     * @return bool
     * @see ArrayIterator::offsetSet()
     */
    public function offsetSet($offset, $val)
    {
        parent::offsetSet($offset, $this->handleMinify($val));

        return true;
    }

    /**
     * Determines whether to point to minified or unmified js file
     * @param string $val
     *
     * @return mixed
     */
    protected function handleMinify($val)
    {
        return Config::minifyJs()
            ? preg_replace('/.(min.)?js/', '.min.js', $val)
            : preg_replace('/.(min.)?js/', '.js', $val);
    }
}