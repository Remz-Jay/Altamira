<?php
/**
 * Class definition for \Altamira\ChartDatum\Bubble
 * @author relwell
 *
 */
namespace Altamira\ChartDatum;

use Altamira\JsWriter\Flot;

/**
 * Class for abstracting bubble chart data.
 *
 * @package ChartDatum
 * @author relwell
 */
class Bubble extends ChartDatumAbstract
{

    /**
     * Constructor method
     * @param array       $dimensions containing x, y, and radius
     * @param string|null $label
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $dimensions, $label = null)
    {
        if ($label !== null) {
            $this->setLabel($label);
        }

        if (!(isset($dimensions['x']) && isset($dimensions['y']) && isset($dimensions['radius']))) {
            throw new \InvalidArgumentException('Altamira\ChartDatum\Bubble requires array keys for x, y, and radius in argument 1.');
        }

        $this['x'] = $dimensions['x'];
        $this['y'] = $dimensions['y'];
        $this['radius'] = $dimensions['radius'];
    }

    /**
     * Returns an array that corresponds with the ultimate JSON data needed for this datum
     * @param bool $useLabel
     *
     * @see Altamira\ChartDatum.ChartDatumAbstract::getRenderData()
     *
     * @return array
     */
    public function getRenderData($useLabel = false)
    {
        $radius = ($this->jsWriter instanceof Flot) ? $this['radius'] * 10 : $this['radius'];

        $data = array($this['x'], $this['y'], $radius);

        if ($useLabel) {
            $data[] = $this['label'];
        }

        return $data;
    }

}