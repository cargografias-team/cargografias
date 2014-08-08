(function(d3,$) {
  
  d3.orgChart = {

    //Default options
    _defaultOptions : {
      margin : {top: 0, right: 10, bottom: 10, left:10},
      width : 100,
      height : 500,
      minYear: 1950,
      maxYear: 2000,
      itemHeight: 70,
      itemMargin: 5,
      hitos:[
          /*{
            date: '1852-01-01',
            desc: 'civil war'
          }*/
          ]
    },

    _options : {
      //vacio
    },

    //chart
    svg: null,
    data: null,

    init: function(containerId,data,options) {
      this._setInitData(data,options,true);
      this.svg = d3.select('#'+containerId).append('svg').attr('height', this._getHeight());
      this._setScales();
      this._setAxis();
      this._render();
      return this;
    },

    _setInitData: function(data,options,first){
      this.data = data;
      d3.orgChart._options = d3.orgChart._defaultOptions;
      $.extend(d3.orgChart._options,options);
      if(first===true) {
        this._setSpecialMargin();
      }

      this._calculateLimits();
    },

    _appendData: function(data){
      this.data = this.data.concat(data);
      this._calculateLimits();
    },

    _calculateLimits: function(){
      d3.orgChart._options.minYear = d3.min(this.data, function(d){return d.cargos[0].inicio;});
      d3.orgChart._options.maxYear = d3.max(this.data, function(d){return d.cargos[0].fin;});
    },

    _setSpecialMargin: function(){
      if(d3.orgChart._options.hitos.length>0) {
        d3.orgChart._options.margin.top += 70;
      }
    },

    _render: function(){

      if(d3.orgChart._options.hitos.length>0) {
        this._renderHito();
      }
         
      this._renderAxis();

      this._renderItems();
    },

    _getHeight: function(){
      return d3.orgChart._options.margin.top + (this.data.length*(d3.orgChart._options.itemHeight+d3.orgChart._options.itemMargin)) + d3.orgChart._options.margin.bottom;
    },


    // SCALES -----------------------------------
    x:null,
    y:null,

    _setScales: function(){
      d3.orgChart.x = d3.time.scale()
        .domain(
          [
            d3.time.year.offset(new Date(d3.orgChart._options.minYear), -1), 
            d3.time.year.offset(new Date(d3.orgChart._options.maxYear), 1)
          ]
          )
        .range([d3.orgChart._options.margin.left, d3.orgChart._options.width - d3.orgChart._options.margin.right]);

      d3.orgChart.y = d3.scale.linear()
        .domain([0, d3.orgChart.data.length])
        .range([d3.orgChart._options.margin.top, (d3.orgChart.data.length*(d3.orgChart._options.itemHeight+d3.orgChart._options.itemMargin)) - d3.orgChart._options.margin.bottom, 0]);
    },

    // AXIS -----------------------------------
    xAxis:null,
    yAxis:null,

    _setAxis: function() {
      d3.orgChart.xAxis = d3.svg.axis()
          .scale(this.x)
          .orient('top')
          .ticks(d3.time.years, 1)
          .tickFormat(d3.time.format('%Y'))
          .tickSize(5)
          .tickPadding(5);

      d3.orgChart.yAxis = d3.svg.axis()
          .scale(d3.orgChart.y)
          .ticks(10)
          .orient('right')
          .tickPadding(8);
    },

    // HITO -----------------------------------
    hitos:null,
    linea:null,

    _renderHito: function(){

      this.hitos = this.svg.selectAll("g")
        .data(d3.orgChart._options.hitos)
        .enter()
        .append("g")
        .attr('class', 'hito-container');

      //CREAR LINEA del HITO
      this.linea = this.hitos.append("line")
        .attr('class', 'hito')
        .attr("x1", function(d){
            return d3.orgChart.x(new Date(d.date));
          } 
        )
        .attr("y1", 0)
        .attr("x2", function(d){
            return d3.orgChart.x(new Date(d.date));
          }
        )
        .attr("y2", this._getHeight());

    },

    _renderAxis: function(){
      //AXIS X
      d3.orgChart.svg.append('g')
        .attr('class', 'x axis')
        .attr('transform', 'translate(0, ' + d3.orgChart._options.margin.top + ')')
        .call(d3.orgChart.xAxis);

    },

    itemsGroup:null,

    itemData:null,

    _renderItems: function(){
      // Dibujo bloque por funcionario
      this.itemsGroup = d3.orgChart.svg.selectAll("g.itemGroup")
        .data(d3.orgChart.data);

      //NEW
      this.itemsGroup = this.itemsGroup.enter().append("g")
        .attr('class','itemGroup')
        .style("opacity", 0)
        .attr("width", d3.orgChart._options.width)
        .attr("id", function(d, i) { return 'item-group-'+i;})
        .attr('height', d3.orgChart._options.itemHeight)
        .attr("transform", function(d, i) {
              //Cálculo de la variación
              var posX = 0;
              var posY = i*(d3.orgChart._options.itemHeight + d3.orgChart._options.itemMargin);
              posY = posY+d3.orgChart._options.margin.top + d3.orgChart._options.itemMargin;
              return ("translate(" + posX + "," + posY + ")");
        });

      //Dibujo bloque para cargo
      this.itemData = this.itemsGroup.append("g")
        .attr('class','bloque')
        .attr("id", function(d, i) { return 'item-data-'+i;})
        .attr("transform", function(d, i) {
              //Cálculo de la variación
              var posX = d3.orgChart.x(new Date(d.cargos[0].inicio));
              var posY = 0;
              return ("translate(" + posX + "," + posY + ")");
          });

      //Dibujo los rect  
      this.itemData.append("rect")
        .attr('class', function (d) {
          return 'cargo tipo-'+ d.cargos[0].tipo_cargo + ' clase-'+ d.cargos[0].clase_cargo + ' cargo-'+ d.cargos[0].cargo.toLowerCase();
        })
        .attr('height', d3.orgChart._options.itemHeight);

      this.colorRect = d3.orgChart.svg.selectAll("rect.cargo");

      this.colorRect
        .transition()
        .duration(1000)
        .attr("width", function (d, i) {
          return (d3.orgChart.x(new Date(d.cargos[0].fin)) - d3.orgChart.x(new Date(d.cargos[0].inicio)));
        })
        .ease("elastic");

      //Le pongo el nombre
      this.itemData.append("text")
        .attr('class','texto-nombre')
        .text(function(d){ return d.nombre;})
        .attr('x',10)
        .attr('y',30);

      //Le pongo el cargo
      this.itemData.append("text")
        .attr('class','texto-cargo')
        .text(function(d){ return d.cargos[0].cargo + ' - ' + d.cargos[0].lugar;})
        .attr('x',10)
        .attr('y',45);

      //Le pongo el partido
      this.itemData.append("text")
        .attr('class','texto-partido')
        .text(function(d){ return d.cargos[0].partido;})
        .attr('x',10)
        .attr('y',60);

      this.itemsGroup
      .transition()
      .duration(500)
      .style("opacity", 1);

    }, // cierro render

    addPage: function(newData){

      this._appendData(newData);

      this.svg.attr('height', this._getHeight());

      this._setScales();

      this._updateScale();

      this._updateHito();

      this._renderItems();

    },

    update: function(newData,newOptions){

      this._setInitData(newData,newOptions);

      this.svg.attr('height', this._getHeight());

      this._setScales();

      this._updateScale();

      this._updateHito();

      this._updateData();

      this._renderItems();

    },

    _updateScale: function(){

      d3.orgChart.svg.selectAll(".x")
          .transition().duration(1000)
          .call(d3.orgChart.xAxis.scale(d3.orgChart.x));

    },

    _updateHito: function(){

      this.linea
        .data(d3.orgChart._options.hitos)
        .transition()
        .duration(1000)
        .attr("x1", function(d){
            return d3.orgChart.x(new Date(d.date));
          } 
        )
        .attr("y1", 0)
        .attr("x2", function(d){
            return d3.orgChart.x(new Date(d.date));
          }
        )
        .attr("y2",  this._getHeight())
        .ease("elastic");

    },

    _updateData: function(){

      // Cargo data vacía para eliminar todo
      this.itemsGroup = d3.orgChart.svg.selectAll("g.itemGroup")
        .data([]);

      // EXIT
      // Remove old elements as needed.
      this.itemsGroup
      .exit()
      .remove();
 
    }

  }

})(d3,jQuery);
