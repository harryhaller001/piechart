// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};


/*
MIT License

Copyright (c) 2019 harryhaller001

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
class PieChart {
	constructor(values, labels, colors) {
		this.values = values;
		this.labels = labels;
		this.colors = colors;
		
		this.header = "";
		this.footer = "";
		
		this.cleanup();		
		this.smoothValues()
	}
	
	sum(total, num) {
  		return total + num;
	}
	
	render(target) {
		var current = 0.0;	
		var chart = "";	
		
		//iterate over values and build piechart	
		for (var i=0; i < this.values.length; i++) {			
			chart+=this.buildHTMLPath( this.buildPath(current, this.values[i]), this.colors[i] );
			current += this.values[i];
		}

		
		target.innerHTML = this.buildSVGElement(chart,this.header, this.footer, this.buildLabels() );
	}
	
	buildSVGElement(piechart, header, footer, labels) {
		return "<svg viewBox='-100 -150 200 350' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='700' height='500' style='background: rgb(255, 255, 255);'>	<style>/* <![CDATA[ */text {	font: 10px arial, sans-serif;}/* ]]> */	</style>	<g id='out' transform='rotate(-90)'>" + piechart + "</g><g id='label'>" + labels + "</g><g transform='translate(0,-130)'><text x='0' y='0' style='text-anchor: middle; font: 14px arial, sans-serif;'>" + header + "</text></g><g id='bottom' transform='translate(0,150)'><text x='0' y='0' style='text-anchor: middle;font: 14px arial, sans-serif;font-weight:bold;'>" + footer + "</text></g></svg>";
	}
	
	buildNode(percent) {
		var a = Math.PI * 2 * percent;
		return [Math.cos(a) * 100, Math.sin(a) * 100];
	}
	
	buildPath(sp,op) {
		var from_coord = this.buildNode(sp);
		var to_coord = this.buildNode(sp + op);	
		var n = 0;	
		if (op > 0.5) {
			n = 1;
		}	
		return "M0,0 L" + from_coord[0] + "," + from_coord[1] +  " A100,100 0 " + n + ",1 " + to_coord[0] + "," + to_coord[1] + " Z";	
	}
	
	buildHTMLPath(d,col) {
		return "<path d='" + d + "' fill='" + col +"' />";
	}
	
	buildLabels() {
		var data = "";	
		var c = 20;
		var yoffset =-1* this.labels.length * c / 2;
		
		for (var i=0; i<this.labels.length; i++) {
			data += this.renderLabel(yoffset + i * c, this.colors[i], this.labels[i]);
		}
		return data;
	}

	renderLabel(yoffset, color, text) {	
		return "<g transform='translate(150," + yoffset + ")'><rect x='-5' y='-5' width='10' height='10' style='fill:" + color + ";text-anchor: middle;alignment-baseline: middle;'></rect><text x='10' y='0' style='text-anchor: start;dominant-baseline: middle;'>" + text + "</text>	</g>";
	}
	
	smoothValues() {
		//sum all values, must be 100%		
		var missing = (1 - this.values.reduce(this.sum)) / this.values.length;		
		for (var i=0; i<this.values;i++) {
			this.values = this.values + missing;
		}
	}
	
	cleanup() {
		var bufv = this.values;
		var bufl = this.labels;
		var bufc = this.colors;
	
		for (var i = 0; i < this.values.length; i++) {
			if (this.values[i] == 0) {
				//cleanup
				bufv.remove(i);
				bufl.remove(i);
				bufc.remove(i);			
			}
		}	
		this.values = bufv;
		this.colors = bufc;
		this.labels = bufl;
	}
	
	setData(vals) {
		this.data = vals;
	}
	
	setHeader(text) {
		this.header = text;
	}
	
	setFooter(text) {
		this.footer = text;
	}
}
