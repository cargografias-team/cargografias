window.cargo = {};
window.cargo.bubblePoderometro = {
    options: {
        width: 960,
        height: 400,
        padding: 40, // separation between nodes
        maxRadius: 60,
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
        if (window.cargo.bubblePoderometro.bubbles != {}) {
            window.cargo.bubblePoderometro.bubbles = window.cargo.bubblePoderometro.bubbles.data(data, function(d) {
                return d;
            });
        }
    },
    start: function(data) {


        var types = [{
            name: "Poder Ejecutivo",
            color: "red",
        }, {
            name: "Poder Legislativo",
            color: "blue",
        }, {
            name: "Poder Judicial",
            color: "darkcyan",
        }, {
            name: "Sin Cargo",
            color: "dark grey",
        }, ];


        var div = d3.select("body").append("div")
            .attr("class", "tooltip")
            .style("opacity", 0);
        this.started = true;
        this.color = d3.scale.linear()
            .domain([0, 3])
            .range(["red", "blue", "darkcyan", "darkgray"]);

        this.x = d3.scale.ordinal()
            .domain(d3.range(1))
            .rangePoints([0, this.options.width], 1);


        this.nodes = data.map(function(d, e) {
            var i = Math.floor(Math.random() * window.cargo.bubblePoderometro.options.m);
            var item = data[e];
            return {
                radius: item.size,
                name: item.name,
                position: item.position,
                type: item.cargo,
                classification: item.classification,
                district: item.district,
                initials: item.initials,
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
            //.call(d3.behavior.zoom().scaleExtent([1, 8]).on("zoom", window.cargo.bubblePoderometro.zoom))
            .append("circle")
            .attr("r", function(d) {
                return d.radius;
            })
            .attr("id", function(d) {
                return d.name
            })
            .style("fill", function(d) {
                if (d.type == "ejecutivo") {
                    return window.cargo.bubblePoderometro.color(0);
                } else if (d.type == "legislativo") {
                    return window.cargo.bubblePoderometro.color(1);
                } else if (d.type == "judicial") {
                    return window.cargo.bubblePoderometro.color(2);
                } else {
                  console.log(d.type, window.cargo.bubblePoderometro.color(3));
                    return window.cargo.bubblePoderometro.color(3);
                }

            });
            //.call(this.force.drag);

        this.svg.selectAll("g")
            .append("text")
            .style("text-anchor", "middle")
            .style("fill", "white")
            .attr('font-size', function(d){
                if (d.radius > 40) {
                    return "13px";
                } else {
                    return "9px";
                }
            })
            .text(function(d) {
                if (d.radius > 40) {
                    return d.name;
                } else if (d.radius >= 5 ) {
                    return d.initials;
                }
            });

        this.svg.selectAll("g")
            .on("mouseover", function(d) {
                div.transition()
                    .duration(200)
                    .style("opacity", .9);
                div.html(d.name + "<br/>" + "<small>" + d.position + "</br>" + d.district + "</small>")
                    .style("left", (d3.event.pageX) + "px")
                    .style("top", (d3.event.pageY - 28) + "px");
            })
            .on("mouseout", function(d) {
                div.transition()
                    .duration(500)
                    .style("opacity", 0);
            });

        
        this.svg.selectAll('.references')
            .data(types)
            .enter()
            .append('text')
            .attr('class','references')
            .attr("x", 750)
            .attr("y", function(d, i) {
                return 30 + (i*30);
            })
            .attr('font-size', "1.3em")
            .text(function(d) {
                return d.name;
            })
            .style("fill", function(d) {
                return d.color;
            });



    },
    zoom:function() {

      window.cargo.bubblePoderometro.svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
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