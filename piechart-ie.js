// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

Array.prototype.clone = function() {
    return [].concat(this)
}


// PieChart - by Malte Hellmig (MIT License)
function PieChart(values, labels, colors) {
	this.values = values.clone();
	this.labels = labels.clone();
	this.colors = colors.clone();
		
	this.header = "";
	this.footer = "";
	
	this.width = 700;
	this.height = 500;
}

PieChart.prototype.setSize = function() {
	this.width = w;
	this.height = h;
}

PieChart.prototype.sum = function(total, num) {
	return total + num;
}

PieChart.prototype.render = function(target) {
		var current = 0.0;	
		var chart = "";	
		
		//iterate over values and build piechart	
		for (var i=0; i < this.values.length; i++) {			
			chart+=this.buildHTMLPath( this.buildPath(current, this.values[i]), this.colors[i] );
			current += this.values[i];
		}		
		target.innerHTML = this.buildSVGElement(chart,this.header, this.footer, this.buildLabels() );
}

PieChart.prototype.buildSVGElement = function(piechart, header, footer, labels) {
		return "<svg viewBox='-100 -150 200 350' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='"+ this.width +"' height='"+ this.height +"' style='background: rgb(255, 255, 255);'>	<style>/* <![CDATA[ */text {	font: 10px arial, sans-serif;}/* ]]> */	</style>	<g id='out' transform='rotate(-90)'>" + piechart + "</g><g id='label'>" + labels + "</g><g transform='translate(0,-130)'><text x='0' y='0' style='text-anchor: middle; font: 14px arial, sans-serif;'>" + header + "</text></g><g id='bottom' transform='translate(0,150)'><text x='0' y='0' style='text-anchor: middle;font: 14px arial, sans-serif;font-weight:bold;'>" + footer + "</text></g></svg>";
}


PieChart.prototype.buildNode = function(percent) {
		var a = Math.PI * 2 * percent;
		return [Math.cos(a) * 100, Math.sin(a) * 100];	
}

PieChart.prototype.buildPath = function(sp,op) {
		var from_coord = this.buildNode(sp);
		var to_coord = this.buildNode(sp + op);	
		var n = 0;	
		if (op > 0.5) {
			n = 1;
		}	
		return "M0,0 L" + from_coord[0] + "," + from_coord[1] +  " A100,100 0 " + n + ",1 " + to_coord[0] + "," + to_coord[1] + " Z";	
}

PieChart.prototype.buildHTMLPath = function(d,col) {
		return "<path d='" + d + "' fill='" + col +"' />";
}


PieChart.prototype.buildLabels = function() {
		var data = "";	
		var c = 20;
		var yoffset =-1* this.labels.length * c / 2;
		
		for (var i=0; i<this.labels.length; i++) {
			data += this.renderLabel(yoffset + i * c, this.colors[i], this.labels[i]);
		}
		return data;
}

PieChart.prototype.renderLabel = function(yoffset, color, text) {	
		return "<g transform='translate(150," + yoffset + ")'><rect x='-5' y='-5' width='10' height='10' style='fill:" + color + ";text-anchor: middle;alignment-baseline: middle;'></rect><text x='10' y='0' style='text-anchor: start;dominant-baseline: middle;'>" + text + "</text>	</g>";
}

PieChart.prototype.smoothValues = function() {
		//sum all values, must be 100%		
		var missing = (1 - this.values.reduce(this.sum)) / this.values.length;		
		for (var i=0; i<this.values;i++) {
			this.values = this.values + missing;
		}
}


PieChart.prototype.cleanup = function() {
		var bufv = [];
		var bufl = [];
		var bufc = [];
	
		for (var i = 0; i < this.values.length; i++) {
			if (this.values[i] != 0) {
				//cleanup
				bufv[i] = this.values[i];
				bufl[i] = this.labels[i];
				bufc[i] = this.colors[i];			
			}
		}	
		this.values = bufv;
		this.colors = bufc;
		this.labels = bufl;
}

PieChart.prototype.setData = function(vals) {
		this.data = vals;
}
	
PieChart.prototype.setHeader = function(text) {
		this.header = text;
}
	
PieChart.prototype.setFooter = function(text) {
		this.footer = text;
}
