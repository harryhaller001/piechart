piechart
========
PieChart written in JS

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
Set target element and render
```javascript
p.render(document.getElementById("target"));
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
