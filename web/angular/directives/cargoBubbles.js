'use strict';
/* Directives */
angular.module('cargoApp.directives').
directive('ngCargobubbles', function() {
    return {
        template: '<div id="bubbles"></div>',
        controller: ['$scope', '$http',
            function($scope) {
                console.log('controller');
            }
        ],
        bubble: {},
        link: function($rootScope, $scope, iElement, iAttrs, ctrl) {

            var startBubbles = function(data) {
               console.log('startbubbles');
                $("#bubbles").html('');
                //BUG: Why is not working?
                // if (window.cargo.bubblePoderometro.started) {
                 //    window.cargo.bubblePoderometro.update(data);
                 //} else {
                    window.cargo.bubblePoderometro.start(data);
                //}

            };


            $rootScope.yearObserver.push(startBubbles);



        }
    }
});

window.cargo = {};
window.cargo.bubblePoderometro = {
    options: {
        width: 960,
        height: 400,
        padding: 40, // separation between nodes
        maxRadius: 12,
        n: 5, // total number of nodes
        m: 1, // number of distinct clusters
    },
    svg: {},
    bubbles: {},
    color: {},
    x: {},
    nodes: {},
    started: false,
    update: function(data) {
        //starts again!
        if(window.cargo.bubblePoderometro.bubbles != {}){
          window.cargo.bubblePoderometro.bubbles = window.cargo.bubblePoderometro.bubbles.data(data, function(d) {
              return d;
          });
        }
    },
    start: function(data) {
        this.started = true;
        this.color = d3.scale.linear()
            .domain([0, 1])
            .range(["blue", "red"]);

        this.x = d3.scale.ordinal()
            .domain(d3.range(1))
            .rangePoints([0, this.options.width], 1);


        this.nodes = data.map(function(d, e) {
            var i = Math.floor(Math.random() * window.cargo.bubblePoderometro.options.m);
            return {
                radius: data[e].size,
                nombre: data[e].name,
                type: data[e].cargo,
                cx: window.cargo.bubblePoderometro.x(i),
                cy: window.cargo.bubblePoderometro.options.height / 2
            };
        });




        this.force = d3.layout.force()
            .nodes(this.nodes)
            .size([this.options.width, this.options.height])
            .gravity(0)
            .charge(0)
            .on("tick", this.tick)
            .start();

        this.svg = d3.select("#bubbles").append("svg")
            .attr("width", this.options.width)
            .attr("height", this.options.height);


        this.bubbles = this.svg.selectAll(".mainNode")
            .data(this.nodes)
            .enter().append("g")
            .attr("class", "mainNode")
            .append("circle")
            .attr("r", function(d) {
                return d.radius;
            })
            .attr("id", function(d) {
                return d.nombre;
            })
            .style("fill", function(d) {
                if (d.type == "ejecutivo") {
                    return window.cargo.bubblePoderometro.color(1);
                } else {
                    return window.cargo.bubblePoderometro.color(0);
                }
            })
            .call(this.force.drag);

        this.svg.selectAll("g").append("text")
            .style("text-anchor", "middle")
            .text(function(d) {
                return d.nombre;
            });

    },
    tick: function(e) {
        window.cargo.bubblePoderometro.svg.selectAll("g")
            .each(window.cargo.bubblePoderometro.gravity(.05 * e.alpha))
            .each(window.cargo.bubblePoderometro.collide(.1))
            .attr("transform", function(d) {
                return "translate(" + d.x + "," + d.y + ")";
            });

    },
    gravity: function(alpha) {
        return function(d) {
            d.y += (d.cy - d.y) * alpha;
            d.x += (d.cx - d.x) * alpha;
        };
    },

    // Resolve collisions between nodes.
    collide: function(alpha) {
        var quadtree = d3.geom.quadtree(this.nodes);
        return function(d) {
            var r = d.radius + window.cargo.bubblePoderometro.options.maxRadius + window.cargo.bubblePoderometro.options.padding,
                nx1 = d.x - r,
                nx2 = d.x + r,
                ny1 = d.y - r,
                ny2 = d.y + r;
            quadtree.visit(function(quad, x1, y1, x2, y2) {
                if (quad.point && (quad.point !== d)) {
                    var x = d.x - quad.point.x,
                        y = d.y - quad.point.y,
                        l = Math.sqrt(x * x + y * y),
                        r = d.radius + quad.point.radius + (d.color !== quad.point.color) * window.cargo.bubblePoderometro.options.padding;
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
    },
    init: function(data, options) {
        this.start(data)
    }
}