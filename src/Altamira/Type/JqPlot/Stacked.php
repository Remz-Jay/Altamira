<?php
/**
 * Class definition for \Altamira\Type\JqPlot\Stacked
 * @author relwell
 *
 */
namespace Altamira\Type\JqPlot;

use \Altamira\Type\TypeAbstract;
/**
 * Registers a chart or series to be rendered as a stacked bar
 *
 * @author relwell
 * @package Type
 * @subpackage JqPlot
 */
class Stacked extends TypeAbstract
{
    const TYPE = 'stacked';

    /**
     * Overwrites defaults in the JqPlot JsWriter
     * @var array
     */
    protected $options = array('stackSeries' => true,
        'seriesDefaults' => array('fill' => true,
            'showMarker' => false,
            'shadow' => false
        )
    );

}

?>