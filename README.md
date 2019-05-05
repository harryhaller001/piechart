piechart
========
PieChart written in JS and PHP.

## piechart.js

Usage
-----
```javascript
var values = [0.333, 0.0833, 0.0833, 0.167, 0.333];
var names = ["Label A","Label B", "Label C", "Label D", "Label E"];
var colors = ["#dd0000", "#007700", "#00ee00", "#dddd00", "#dd00dd"];
```
Create PieChart instance with three arrays.
```javascript
var p = new PieChart(values, names, colors);
```
Set header and footer text.
```javascript
p.setHeader("PieChart Header");
p.setFooter("Footer Text");
```

Remove empty values from array.
```javascript
p.cleanup();
```

Set target element and render.
```javascript
p.render(document.getElementById("target"));
```

## piechart.php

This project uses php-svg (https://github.com/meyfa/php-svg). The `fonts` directory is needed for rendering.

Usage
-----
```php
$values = array(0.333, 0.0833, 0.0833, 0.167, 0.333);
$names = array("Label A","Label B", "Label C", "Label D", "Label E");
$colors = array("#dd0000", "#007700", "#00ee00", "#dddd00", "#dd00dd");
```
Create PieChart instance from array.
```php
$piechart = new PieChart($values, $names, $colors);
```
Set header and footer text.
```php
$piechart->setHeader("PieChart Header");
$piechart->setFooter("Footer Text");
```
Remove empty values from array and smooth values.
```php
$piechart->cleanup();
$piechart->smoothValues();
```
Set size, radius and background-color of PieChart
```php
$piechart->setSize(750,500);
$piechart->setRadius(160);
$piechart->setBackground("#FFFFFF");
```
Render to SVG
```php
header("Content-Type: image/svg+xml");
echo $piechart->toSVG();
```

Render to PNG
```php
header("Content-Type: image/png");
$image = $piechart->render();
$rasterImage = $image->toRasterImage(1500, 1000);
imagepng($rasterImage);
```

License
-------
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
