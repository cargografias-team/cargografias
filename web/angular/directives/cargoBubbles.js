'use strict';

/* Directives */



angular.module('cargoApp.directives').
  directive('ngCargobubbles', function() {
    return {
      template: '<div id="bubbles"></div>',
      controller: ['$scope', '$http', function($scope) {
          console.log('controller');
      }],
      link: function($rootScope, $scope, iElement, iAttrs, ctrl) {

        
        var startBubbles = function(data){
          
          $("#bubbles").html('');
          var width = 960,
            height = 400,
            padding = 40, // separation between nodes
            maxRadius = 12;

          var n = 5, // total number of nodes
            m = 1; // number of distinct clusters


          var color = d3.scale.linear()
            .domain([0,1])
            .range(["blue","red"]);

          var x = d3.scale.ordinal()
            .domain(d3.range(1))
            .rangePoints([0, width], 1);


          var nodes = data.map(function(d,e) {
          var i = Math.floor(Math.random() * m);
          return {
            radius: data[e].size,
            nombre: data[e].name,
            type: data[e].cargo,
            cx: x(i),
            cy: height / 2
          };
          });




          var force = d3.layout.force()
            .nodes(nodes)
            .size([width, height])
            .gravity(0)
            .charge(0)
            .on("tick", tick)
            .start();

          var svg = d3.select("#bubbles").append("svg")
            .attr("width", width)
            .attr("height", height);


          var mainNode = svg.selectAll(".mainNode")
            .data(nodes)
          .enter().append("g")
          .attr("class","mainNode")
          .append("circle")
            .attr("r", function(d) { return d.radius; })
            .attr("id",function(d) { return d.nombre; })
            .style("fill", function(d) { 
                if (d.type=="ejecutivo") {
                  return color(1);  
                }else{
                  return color(0);  
                }
                 })
            .call(force.drag);
              
          svg.selectAll("g").append("text")
            .style("text-anchor","middle")
            .text(function(d) { return d.nombre; });



          

          function tick(e) {
          svg.selectAll("g")
              .each(gravity(.05 * e.alpha))
              .each(collide(.1))
              .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

          }

          // Move nodes toward cluster focus.
          function gravity(alpha) {
          return function(d) {
            d.y += (d.cy - d.y) * alpha;
            d.x += (d.cx - d.x) * alpha;
          };
          }

          // Resolve collisions between nodes.
          function collide(alpha) {
          var quadtree = d3.geom.quadtree(nodes);
          return function(d) {
            var r = d.radius + maxRadius + padding,
                nx1 = d.x - r,
                nx2 = d.x + r,
                ny1 = d.y - r,
                ny2 = d.y + r;
            quadtree.visit(function(quad, x1, y1, x2, y2) {
              if (quad.point && (quad.point !== d)) {
                var x = d.x - quad.point.x,
                    y = d.y - quad.point.y,
                    l = Math.sqrt(x * x + y * y),
                    r = d.radius + quad.point.radius + (d.color !== quad.point.color) * padding;
                if (l < r) {
                  l = (l - r) / l * alpha;
                  d.x -= x *= l;
                  d.y -= y *= l;
                  quad.point.x += x;
                  quad.point.y += y;
                }
              }
              return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
            });
          };
          }
          
          
         };


         $rootScope.yearObserver.push(startBubbles);



      }
    }
 });


