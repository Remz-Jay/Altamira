<?php
/**
 * Class definition for \Altamira\ChartRenderer\RendererInterface
 * @author relwell
 *
 */
namespace Altamira\ChartRenderer;

use Altamira\Chart;

/**
 * Builds a standard interface about how we render HTML items
 * This interface allows for opening and closing tags around other logic, as well as style
 */
interface RendererInterface
{
    /**
     * A hook for including things like opening tags
     * @param Chart   $chart
     * @param array   $styleOptions
     */
    public static function preRender(Chart $chart, array $styleOptions = array());

    /**
     * A hook for including things like closing tags
     * @param Chart   $chart
     * @param array   $styleOptions
     */
    public static function postRender(Chart $chart, array $styleOptions = array());

    /**
     * Used to hold metadata on style about the item rendered
     * @param array $styleOptions
     */
    public static function renderStyle(array $styleOptions = array());

}