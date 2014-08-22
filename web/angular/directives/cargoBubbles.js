'use strict';

/* Directives */

/* based on http://bl.ocks.org/mbostock/4063269 */

angular.module('cargoApp.directives').
  directive('ngCargobubbles', function() {
    return {
    	template: '<div id="bubbles"></div>',
    	controller: ['$scope', '$http', function($scope, $http) {
    			console.log('controller');
    			$scope.one = "one";
    	}],
    	link: function($rootScope, $scope, iElement, iAttrs, ctrl) {
    		//$rootScope.startBubbles= function(redraw){}
        var data ={
            name: "analytics",
             children: [
              {
               name: "cluster",
               children: [
                {name: "Cristina Fernandez",size: 3938},
                {name: "Nestor Kirchner",size: 3812},
                {name: "Carlos Mendez",size: 6714},
                {name: "Martin Loustau",size: 743}
               ]
              },
              {
               name: "graph",
               children: [
                {name: "Eduardo Bauza",size: 3534},
                {name: "Carlos Ruckauf",size: 5731},
                {name: "Palito Ortega",size: 7840},
                {name: "Raul Alfonsin",size: 5914},
                {name: "Carlos Alvarez",size: 3416}
               ]
              },
              {
               name: "optimization",
               children: [
                {name: "Fernando de la Rua", size: 7074}
               ]
              }
             ]
          };
          var diameter = 960/2,
                format = d3.format(",d"),
                color = d3.scale.category20c();

            var bubble = d3.layout.pack()
                .sort(null)
                .size([diameter, diameter])
                .padding(1.5);

            var svg = d3.select("#bubbles").append("svg")
                .attr("width", diameter)
                .attr("height", diameter)
                .attr("class", "bubble");

            
            var node = svg.selectAll(".node")
                .data(bubble.nodes(classes(data))
                .filter(function(d) { return !d.children; }))
              .enter().append("g")
                .attr("class", "node")
                .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

            node.append("title")
                .text(function(d) { return d.className + ": " + format(d.value); });

            node.append("circle")
                .attr("r", function(d) { return d.r; })
                .style("fill", function(d) { return color(d.packageName); });

            node.append("text")
                .attr("dy", ".3em")
                .style("text-anchor", "middle")
                .text(function(d) { return d.className.substring(0, d.r / 3); });
            

            // Returns a flattened hierarchy containing all leaf nodes under the root.
            function classes(root) {
              var classes = [];

              function recurse(name, node) {
                if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
                else classes.push({packageName: name, className: node.name, value: node.size});
              }

              recurse(null, root);
              return {children: classes};
            }

            d3.select(self.frameElement).style("height", diameter + "px");
    			
    	   //};

    	}
    }
 });


