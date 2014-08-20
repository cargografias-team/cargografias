(function(d3,$) {
  
  d3.cargoChart = function(containerId,data) {

    var g = d3.select('#'+containerId)

    g.append("ul")
      .selectAll("li")
      .data(data)
      .enter()
      .append("li")
      .text(function(d){
        return d.nombre;
      })
      .append("ul")
      .selectAll("li")
      .data(function (d) { return d.cargos; })
      .enter()
      .append("li")
      .text(function(d){
        //console.log(d);
        return d.cargo + ' ' + d.inicio + '-' + d.fin;
      });
  }

})(d3,jQuery);
