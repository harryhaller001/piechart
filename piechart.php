<?php

/*This project uses php-svg (See https://github.com/meyfa/php-svg for more information). Under MIT License below:

	The MIT License (MIT)

	Copyright (c) 2015 - 2018 Fabian Meyer

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.
*/

require_once __DIR__.'/php-svg/autoloader.php';

use SVG\SVG;
use SVG\Nodes\Shapes\SVGCircle;
use SVG\Nodes\Structures\SVGStyle;
use SVG\Nodes\Structures\SVGGroup;
use SVG\Nodes\Shapes\SVGPath;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Texts\SVGText;
use \SVG\Nodes\Structures\SVGFont;



/*
*	PieChart - Copyright (c) 2019 Malte Hellmig (MIT License)
*
*	//Usage:
*	
*	$Values = array(0.3,0.2,0.5);
*	$Labels = array("A","B","C");
*	$Colors = array("rgb(0,255,255)", "rgb(0,0,255)", "rgb(255,0,0)");
*	$p = new PieChart( $Values, $Labels, $Colors );
*
*	$p->setHeader("Header text");
*	$p->setFooter("Footer text");
*
*	//Version 1 : print out SVG String
*	$svg_text = $p->toSVG();
*	echo $svg_text;
*	header("Content-Type: text/svg+xml");
*
*	//Version 2 : print out Binary data
*	$raster_image = $p->toImage();
*	imagepng($raster_image);
*	header("Content-Type: image/png");
*
*/
class PieChart {

	private $Values;
	private $Labels;
	private $Colors;
	
	private $Height;
	private $Width;
	private $Radius;	
	private $Center;	
	private $Yoffset;
	private $Xoffset;	
	private $LabelSize;
	
	private $Header;
	private $Footer;
	
	private $length;
	
	private $Font;
	private $FontPath;
	
	private $BackgroundColor;
	
	
	public function __construct($values, $labels, $colors) {
		//Input variables
		$this->Colors = $colors;
		$this->Labels = $labels;
		$this->Values = $values;	
		
		//Set default size
		$this->Width = 750;
		$this->Height = 500;
		$this->Radius = 150;
		$this->LabelSize = 14;
		
		//Load default Font Arial
		$this->FontPath = dirname(__FILE__) . '/fonts/arial.ttf';
		$this->Font = new SVGFont('Arial', $this->FontPath);
		
		//Adjust rendering parameters
		$this->Yoffset = ($this->Height - 2 * $this->Radius) / 2;
		$this->Xoffset = ($this->Width - 2 * $this->Radius) / 2;
		$this->Center = array(intval($this->Width / 2), intval($this->Height / 2) );
		$this->BackgroundColor = "#FFFFFF";
		
		$this->Header = "Piechart";
		$this->Footer = "";		
		
		$this->length = sizeof($labels);			
	}
	
	/**
	* Set Size of PieChart SVG
	*
	* @param $w
	* @param $h
	* @return $this
	*/		
	public function setSize($w, $h) {
		$this->Width = $w;
		$this->Height = $h;
		$this->Center = array(intval($this->Width / 2), intval($this->Height / 2) );
		$this->Yoffset = ($this->Height - 2 * $this->Radius) / 2;
		$this->Xoffset = ($this->Width - 2 * $this->Radius) / 2;	
		return $this;		
	}
	
	/**
	* Set background color as RGB String (e.g. "#FFFFFF")
	*
	* @return $this
	**/
	public function setBackground($c) {
		$this->BackgroundColor = $c;
		return $this;
	}
	
	/**
	* Set Piechart radius
	*
	* @return $this
	**/
	public function setRadius($r) {
		$this->Radius = $r;
		return $this;
	}
	
	/**
	* Set header text
	*
	* @return $this
	**/
	public function setHeader($s) {
		$this->Header = $s;
		return $this;
	}
	
	/**
	* Set footer text
	*
	* @return $this
	**/
	public function setFooter($s) {
		$this->Footer = $s;
		return $this;
	}
	
	public function checkValues() {	
		if (sizeof($this->Labels) != sizeof($this->Values) || sizeof($this->Values) != sizeof($this->Colors)) {
			return False;
		} else {
			return True;
		}		
	}
	
	/**
	* Smooth input values, remove zero values
	*
	* @return $this
	**/	
	public function smoothValues() {
		//init buffer arrays
		$buffer_val = array();
		$buffer_col = array();
		$buffer_lab = array();		
		for ($i = 0; $i < sizeof($this->Values); $i++) {
			if ($this->Values[$i] > 0.01) {
				array_push($buffer_val, $this->Values[$i]);
				array_push($buffer_col, $this->Colors[$i]);
				array_push($buffer_lab, $this->Labels[$i]);
			}
		}		
		//overwrite array with smoothed values
		$this->Labels = $buffer_lab;
		$this->Colors = $buffer_col;
		$this->Values = $buffer_val;			
		$this->length = sizeof($this->Values);
		return $this;	
	}
	
	public function getCoord($percent) {
		$a = pi() * 2 * $percent;	
		return array($this->Center[0] + cos($a) * $this->Radius, $this->Center[1] + sin($a) * $this->Radius);
	}

	public function buildPath($start, $offset) {			
		$from_coord = $this->getCoord($start - 0.25);		
		$to_coord = $this->getCoord($start + $offset - 0.25);
		
		if ($offset > 0.5) {
			$n = 1;
		} else {
			$n = 0;	
		}		
		return "M" . $this->Center[0] . "," . $this->Center[1] . " L" . $from_coord[0] . "," . $from_coord[1] .  " A" . $this->Radius . "," . $this->Radius . " 0 " . $n . ",1 " . $to_coord[0] . "," . $to_coord[1] . " Z";	
	}
	
	public function getFontWidth($t, $s) {	
		$matrix = imagettfbbox($s, 0, $this->FontPath, $t );
		return abs($matrix[2] - $matrix[0]);
	}
	
	public function getFontHeight($t, $s) {
		$matrix = imagettfbbox($s, 0, $this->FontPath, $t );
		return abs($matrix[7] - $matrix[1]);
	}
	
	public function render() {
		//Check
		if (!$this->checkValues()) {
			return "";			
		}
		
		//Adjust parameters
		$this->Center = array(intval($this->Width / 2), intval($this->Height / 2) );
		$this->Yoffset = ($this->Height - 2 * $this->Radius) / 2;
		$this->Xoffset = ($this->Width - 2 * $this->Radius) / 2;	
		$this->length = sizeof($this->Labels);	
		
		
		//Build SVG
		$image = new SVG($this->Width, $this->Height);
		$doc = $image->getDocument();
			
		//Define Background
		$doc->setStyle("background",$this->BackgroundColor);		
		$background = new SVGRect(0,0,$this->Width, $this->Height);
		$background->setStyle("fill", $this->BackgroundColor);
		$doc->addChild($background);

		//Add Paths to PieChart Container
		$group_out = new SVGGroup();
		$group_out->setAttribute("id", "out");
		
		//Iterate over Values!
		$current = 0.0;
		
		for ($i=0; $i < $this->length; $i++ ) {	
		
			$p1 = new SVGPath( $this->buildPath($current, $this->Values[$i]) );
			$current += $this->Values[$i];	
	
			$p1->setStyle("fill", $this->Colors[$i]);
			$group_out->addChild($p1);
		}
		
		//Add Labels
		$group_labels = new SVGGroup();
		$group_labels->setAttribute("id", "labels");
		
				
		for ( $i = 0; $i < $this->length; $i++ ) {
	
			$iter_group = new SVGGroup();

			$rect_x = $this->Center[0] + $this->Xoffset + (-0.5 * $this->LabelSize);
			$rect_y = $this->Center[1] + -0.5 * $this->LabelSize + ($this->length * (-1 * $this->LabelSize) + $i  * (2* $this->LabelSize));
			
			$iter_rect = new SVGRect($rect_x,$rect_y , $this->LabelSize, $this->LabelSize);			
			$iter_rect->setStyle("fill",$this->Colors[$i]);						
				
			$iter_text = new SVGText( $this->Labels[$i], $rect_x + 2 * $this->LabelSize, $rect_y + ($this->LabelSize / 2) + ($this->getFontHeight($this->Labels[$i], 10.5)  / 2)  );
			$iter_text->setFont($this->Font);
			$iter_text->setSize("10.5pt");
				
			$iter_group->addChild($iter_rect);
			$iter_group->addChild($iter_text);
	
			$group_labels->addChild($iter_group);
		}		
		
		//Add Header Text
		$group_header = new SVGGroup();
		$group_header->setAttribute("id", "top");
				
		$header_text = new SVGText($this->Header, $this->Center[0] - ($this->getFontWidth($this->Header, 13.5) / 2), $this->Center[1] + -1*$this->Radius - ($this->Yoffset / 2));
		$header_text->setFont($this->Font);
		$header_text->setSize("18px");

		$group_header->addChild($header_text);
		
		//Add Footer Text
		$group_footer = new SVGGroup();
		$group_footer->setAttribute("id", "bottom");

		$footer_text = new SVGText($this->Footer, $this->Center[0] - ($this->getFontWidth($this->Footer, 13.5) / 2), $this->Center[1] + $this->Radius + ($this->Yoffset / 2));
		$footer_text->setFont($this->Font);
		$footer_text->setSize("18px");		
		
		$group_footer->addChild($footer_text);
		
		//Add Group Items to Main Document
		$doc->addChild($group_out);
		$doc->addChild($group_labels);
		$doc->addChild($group_header);
		$doc->addChild($group_footer);		
		
		return $image;
	}
	
	/**
	* Render Piechart to XML String
	*
	**/
	public function toSVG() {
		return $this->render()->toXMLString();
	}
	
	/**
	* Render Piechart to Rasterimage
	**/
	public function toImage() {	
		return $this->render()->toRasterImage($Width, $Height);
	}
}


?>