<?php

namespace Altamira;

use Altamira\JsWriter\JsWriterAbstract;

class Series
{
	static protected $count = 0;
	protected $data = array();
	protected $tags = array();
	protected $useTags = false;
	protected $useLabels = false;

	protected $jsWriter;
	
	protected $title;
	protected $labels= array();
	protected $files = array();
	protected $allowedOptions = array('lineWidth', 'showLine', 'showMarker', 'markerStyle', 'markerSize');

	public function __construct($data, $title = null, JsWriterAbstract $jsWriter)
	{
		self::$count++;
		
		$this->jsWriter = $jsWriter;

		$tagcount = 0;
		foreach($data as $datum) {
			if(is_array($datum) && count($datum) >= 2) {
				$this->useTags = true;
				$this->data[] = array_shift($datum);
				$this->tags[] = array_shift($datum);
			} else {
				$this->data[] = $datum;
				if(count($this->tags) > 0) {
					$this->tags[] = end($this->tags) + 1;
				} else {
					$this->tags[] = 1;
				}
			}
			$tagcount++;
		}

		if(isset($title)) {
			$this->title = $title;
		} else {
			$this->title = 'Series ' . self::$count;
		}
	}

	public function getFiles()
	{
		return $this->files;
	}

	public function setSteps($start, $step)
	{
		$num = $start;
		$this->tags = array();

		foreach($this->data as $item) {
			$this->tags[] = $num;
			$num += $step;
		}
	}

	public function setShadow($opts = array('use'=>true, 
                                            'angle'=>45, 
                                            'offset'=>1.25, 
                                            'depth'=>3, 
                                            'alpha'=>0.1))
	{
	    if (! $this->jsWriter instanceOf \Altamira\JsWriter\Ability\Shadowable ) {
	        throw new \BadMethodCallException("JsWriter not Shadowable");
	    }
	    
		$this->jsWriter->setShadow($this->getTitle(), $opts);
		
		return $this;
	}

	public function setFill($opts = array('use' => true, 
                                                   'stroke' => false, 
                                                   'color' => null, 
                                                   'alpha' => null
                                                  ))
	{
        if (! $this->jsWriter instanceOf \Altamira\JsWriter\Ability\Fillable ) {
            throw new \BadMethodCallException("JsWriter not Fillable");
        }
	    
	    $this->jsWriter->setFill($this->getTitle(), $opts);
	    
		return $this;
	}

	public function getData($tags = false)
	{
		if($tags || $this->useTags) {
			$labels = $this->labels;
			if($this->useLabels && (count($labels) > 0)) {
				$useLabels = true;
			} else {
				$useLabels = false;
			}

			$data = array();
			$tags = $this->tags;
			foreach($this->data as $datum) {
				if(is_array($datum)) {
					$item = $datum;
					$item[] = array_shift($tags);
				} else {
					$item = array($datum, array_shift($tags));
				}
				if($useLabels) {
					if(count($labels) === 0) {
						$item[] = null;
					} else {
						$item[] = array_shift($labels);
					}
				}

				$data[] = $item;
			}
			return $data;
		} else {
			return $this->data;
		}
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function useLabels($labels = array())
	{
		$this->useTags = true;
		$this->useLabels = true;
        $this->setOption('pointLabels', array('show' => true, 'edgeTolerance' => 3));
		$this->labels = $labels;

		return $this;
	}

	// @todo this logic should probably be in the jswriter
	public function setLabelSetting($name, $value)
	{
		if($name === 'location' && in_array($value, array('n', 'ne', 'e', 'se', 's', 'sw', 'w', 'nw'))) {
		    $this->setOption('pointLabels', (($a = $this->getOption('pointLabels')) && is_array($a) ? $a : array()) + array('location'=>$value));
		} elseif(in_array($name, array('xpadding', 'ypadding', 'edgeTolerance', 'stackValue'))) {
			$this->setOption('pointLabels', (($a = $this->getOption('pointLabels')) && is_array($a) ? $a : array()) + array($name=>$value));
		}

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setOption($name, $value)
	{
		if(in_array($name, $this->allowedOptions)) {
			$this->jsWriter->setSeriesOption($this->getTitle(), $name, $value);
		}

		return $this;
	}
	
	public function getOption($option)
	{
	    return $this->jsWriter->getSeriesOption($this->getTitle(), $option);
	}

	public function getOptions()
	{
        return $this->jsWriter->getOptionsForSeries($this->getTitle());
	}
	
	public function usesLabels()
	{
	    return isset($this->useLabels) && $this->useLabels === true;
	}
}
