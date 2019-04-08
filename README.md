## piechart
PieChart written in JS

#Usage
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

#License
