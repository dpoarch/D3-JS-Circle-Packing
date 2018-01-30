<!DOCTYPE html>
<meta charset="utf-8">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<title>Circle Packing</title>
<style>
body{
  font-family: sans-serif;
}
.node {
  cursor: pointer;
  fill: #2173b5;
  fill-opacity: 1;
  stroke: #7b7b7b;
  stroke-width: 1.5px;
}

.node:hover {
  stroke: #000;
  stroke-width: 1.5px;
}

.node--leaf {
  fill: white;
}

.label {
  font: 15px "Helvetica Neue", Helvetica, Arial, sans-serif;
  text-anchor: middle;
  text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff;
}
text {
  font: 19px sans-serif;
  text-anchor: middle;
  font-weight: bold;
}
.depth {
    float: right;
    width: 30%;
}
.legend{
    margin-top: 150px;
    margin-right: 200px;
    border: 4px solid #585858;
    border-radius: 10px;
    padding: 10px;
}

.hide{
  opacity: 0;
}
.levels{
  width: 20px;
  float: left;
  margin-top: -7px;
}
#slider{
  float: left;
}
.levels div{
  padding-bottom: 30px;
}
.label,
.node--root,
.node--leaf {
  pointer-events: none;
}

.color1{
  background-color:  #043927;
}

.color2{
  background-color:  #4b5320;
}

.color3{
  background-color:  #0b6623;
}

.color4{
  background-color:  #3f714e;
}

.color5{
  background-color:  #507843;
}

.color6{
  background-color:  #2e8a57;
}

.color7{
  background-color:  #4cbb17;
}

.color8{
  background-color:  #39ff14;
}

.color9{
  background-color:  #c7ea46;
}

.color10{
  background-color:  #97fb98;
}

.color11{
  background-color:  #d0f0bf;
}

.color{
    width: 20px;
    height: 20px;
}

.information{
  border: 4px solid #585858;
  border-radius: 10px;
  padding: 10px;
  margin-top: 30px;
  height: 310px;
  overflow-y: scroll;
}

.information p{
    display: inline-block;
}

.note{
  color: #2968ab;
}

</style>


<div class="depth">
    <p>
      <label for="depth">Depth</label>
      <input type="text" id="depth" readonly style="border:0; color:#f6931f; font-weight:bold;">
    </p>
    <div class="levels">
      <div>3</div>
      <div>2</div>
      <div>1</div>
    </div>
    <div id="slider"></div>
    <br>
    <table class="legend">
    <tr><th colspan="2">Legend</th></tr>
    <tr><td class="color color1"></td><td> .1 - 1</td></tr>
    <tr><td class="color color2"></td><td> .09 - .1</td></tr>
    <tr><td class="color color3"></td><td> .08 - .09</td></tr>
    <tr><td class="color color4"></td><td> .07 - .08</td></tr>
    <tr><td class="color color5"></td><td> .06 - .07</td></tr>
    <tr><td class="color color6"></td><td> .05 - .06</td></tr>
    <tr><td class="color color7"></td><td> .04 - .05</td></tr>
    <tr><td class="color color8"></td><td> .03 - .04</td></tr>
    <tr><td class="color color9"></td><td> .02 - .03</td></tr>
    <tr><td class="color color10"></td><td> .01 - .02</td></tr>
    <tr><td class="color color11"></td><td> .001 - .01</td></tr>

  </table>

  <p class="note"><strong>Note:</strong> click a node/circle to see information.</p>

  <div class="information">
    <div><strong>Name: </strong><p id="name"></p></div>
    <div><strong>Size: </strong><p id="size"></p></div>
    <div><strong>Number of Documents: </strong><p id="docs"></p></div>
    <div><strong>Level: </strong><p id="level"></p></div>
    <div><strong>Density: </strong><p id="density"></p></div>
  </div>
</div>


<svg width="960" height="960"></svg>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

var svg = d3.select("svg"),
    margin = 20,
    diameter = +svg.attr("width"),
    g = svg.append("g").attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");


var color = d3.scaleLinear()
    .domain([-1, 5])
    .range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
    .interpolate(d3.interpolateHcl);

var pack = d3.pack()
    .size([diameter - margin, diameter - margin])
    .padding(2);

d3.json("Overview.json", function(error, root) {
  if (error) throw error;

  root = d3.hierarchy(root)
      .sum(function(d) { return d.size; })
      .sort(function(a, b) { return b.value - a.value; });

  var focus = root,
      nodes = pack(root).descendants(),
      view;

  var circle = g.selectAll("circle")
    .data(nodes)
    .enter().append("circle")
      .filter(function(d) { return d.data.level != "0"; })
      .attr("r", function(d) { return d.r; })
      .attr("level", function(d) { return d.data.level; })
      .attr("id", function(d){ 
        return d.data.name.replace(/\s/g,''); 
      })
      .attr("class", function(d) {
        var newclass = "";
        if(d.data.level != "1"){ newclass += "hide "; }
        if(d.parent){ newclass += "node"; }else if(d.children){ newclass += "node node--leaf"; }else{ newclass += "node node--root"; };
        return newclass; 
      })
      .style("fill", function(d) { 
        var color = "#043927";
        if(d.data.density <= 1 && d.data.density >= .1){
          color = "#043927";
        }else if(d.data.density < .1 && d.data.density >= .09){
          color = "#4b5320";
        }
        else if(d.data.density < .09 && d.data.density >= .08){
          color = "#0b6623";
        }
        else if(d.data.density < .08 && d.data.density >= .07){
          color = "#3f714e";
        }
        else if(d.data.density < .07 && d.data.density >= .06){
          color = "#507843";
        }
        else if(d.data.density < .06 && d.data.density >= .05){
          color = "#2e8a57";
        }
        else if(d.data.density < .05 && d.data.density >= .04){
          color = "#4cbb17";
        }
        else if(d.data.density < .04 && d.data.density >= .03){
          color = "#39ff14";
        }
        else if(d.data.density < .03 && d.data.density >= .02){
          color = "#c7ea46";
        }
        else if(d.data.density < .02 && d.data.density >= .01){
          color = "#97fb98";
        }
        else if(d.data.density < .01 && d.data.density >= .001){
          color = "#d0f0bf";
        }
        return color; 
      })
      .on("click", function(d) { 
        if (focus !== d) zoom(d), d3.event.stopPropagation();
        $("#name").text(d.data.name);
        $("#level").text(d.data.level);
        $("#size").text(d.data.size);
        $("#docs").text(d.data.numDocs);
        $("#density").text(d.data.density);
      });

  var text = g.selectAll("text")
    .data(nodes)
    .enter().append("text")
      .attr("class", function(d) { 
        var newclass = "";
        if(d.data.level != "1"){ newclass += "hide "; }
        newclass += "label";
        return newclass; 
      })
      .attr("level", function(d) { return d.data.level; })
      .attr("size", function(d) { return d.data.size; })
      .attr("id", function(d) { 
        var str = d.data.name.replace(/\s/g,'');
        return "txt-" + str; 
      })
      .style("fill-opacity", function(d) { return d.parent === root ? 1 : 0; })
      .style("display", function(d) { return d.parent === root ? "inline" : "none"; })
      .text(function(d) { return d.data.name; });

  var node = g.selectAll("circle,text");

  svg.on("click", function() { zoom(root); });

  zoomTo([root.x, root.y, root.r * 2 + margin]);


  d3.selectAll("g circle").each( function(d, i){
    if(d3.select(this).attr("level") != "1"){
      d3.select(this)
      .on("mouseover", function(d){ 
          var str = d3.select(this).attr("id");
          if($("#depth").val() != 1){
            d3.selectAll("#txt-" + str).each(function(d, i){
              d3.select(this).style("opacity", "1").style("display", "block").style("fill-opacity", "1");
            });
          }
        })
        .on("mouseout", function(d){ 
          var str = d.data.name.replace(/\s/g,'');
          if($("#depth").val() != 1 ){
              d3.selectAll("#txt-" + str).each(function(d, i){
                //if(d3.select(this).attr("size") < 101 && $("#depth").val() == 1 ){
                  d3.select(this).style("opacity", "0").style("display", "none").style("fill-opacity", "0");
                //}
              });
          }
      });
    }
  });

  function zoom(d) {
    var focus0 = focus; focus = d;

    var transition = d3.transition()
        .duration(d3.event.altKey ? 7500 : 750)
        .tween("zoom", function(d) {
          var i = d3.interpolateZoom(view, [focus.x, focus.y, focus.r * 2 + margin]);
          return function(t) { zoomTo(i(t)); };
        });

    transition.selectAll("text")
      .filter(function(d) { return d.parent === focus || this.style.display === "inline"; })
        .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
        .on("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
        .on("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
  }

  function zoomTo(v) {
    var k = diameter / v[2]; view = v;
    node.attr("transform", function(d) { return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")"; });
    circle.attr("r", function(d) { return d.r * k; });
  }
});



$(function() {
  $( "#slider" ).slider({
    value:1,
    min: 1,
    max: 3,
    step: 1,
    orientation: "vertical",
    slide: function( event, ui ) {
      $("#depth").val(ui.value );
      d3.selectAll("g circle").each( function(d, i){
          if(d3.select(this).attr("level") <= ui.value){
            d3.select(this).transition().style("opacity", "1");
          }else{
            d3.select(this).transition()
            .style("opacity", "0");
          }
      });
      d3.selectAll("g text").each( function(d, i){
          if(d3.select(this).attr("level") <= ui.value && d3.select(this).attr("size") > 100){
            d3.select(this).transition().style("opacity", "1");
          }else{
            d3.select(this).transition()
            .style("opacity", "0");
          }
      });
    }
  });
  $( "#depth" ).val($( "#slider" ).slider( "value" ) );
});

</script>