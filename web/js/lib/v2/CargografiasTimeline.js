(function(document, window, d3, _, $) {
  "use strict";

  var thisYear = (new Date()).getFullYear();

  var tooltipTemplate =
    '<h2><strong><%- persona.nombre + " " + persona.apellido %></strong></h2>' +
    '<strong><%- nominal.nombre %> </strong><br>' +
    '<span class="ctl-detalles">' +
    '<%- fechainicioyear %> - <%- fechafinyear %><br>' +
    '<%- nominal.tipo %><br>' + 
    '<%- territorio.nombre %>' +
    '</span>';

  var svgTemplate =
    '<svg>' +
    '<g class="ctl-fondo"></g>' +
    '<g class="ctl-ejeCargos0"></g>' +
    '<g class="ctl-ejeCargos1"></g>' +
    '<g class="ctl-ejePersonas"></g>' +
    '<g class="ctl-cargos"></g>' +
    '<g class="ctl-curvas"></g>' +
    '</svg>' +
    '<div class="ctl-tooltip"></div>';

  window.CargografiasTimeline = function(options) {

    this.options = options;
    var $scope = angular.element($("#cargoContainer")).scope();
    // "Constantes"
    var CHART_WIDTH = $(options.containerEl).width() - 20 ;
    var CHART_HEIGHT = 0; //Not a constant anymore, hay que renombrar

    var PIXELS_PER_YEAR = 20;
    var ALTO_BLOQUES = 20; //Alto de los bloques
    var PIXELS_H_OFFSET = 100;
    var TRANSITION_DURATION = 1000;
    var OFFSET_Y = 40; // USado para mover verticalmente los  blques y el eje vertical
    var EJE_ANIOS_OFFSET_Y = 15;
    var ANIO_INICIAL = 2001 ; //$scope.activeYear || 
    var x0 = $scope.activeYear = ANIO_INICIAL;
    var ALTURA_OCULTAMIENTO = -100; // Los elementos se van a mover acá cuando no se muestren

    var mostrandoPor = "nombre";

    var svg;

    //Estos 2 valores se recalculan después
    var primerStartingYear = 2000;
    var ultimoEndingYear = 2000;

    var data = normalizarDatos(__cargos_data);

    CHART_HEIGHT = ALTO_BLOQUES * _.size(_.groupBy(data.cargos, function(d) {
      return d.persona_id;
    })) + 50;

    resetSVGCanvas();

    var xScale = buildxScale(data);

    // Esto inicializa los rectángulos que representan a los cargos
    var groupsCargo = inicializarCargosBloques(data);

    initTooltip();

    //global ejes
    var ejes = {
      ejeCargos0: svg.select('.ctl-ejeCargos0'),
      ejeCargos1: svg.select('.ctl-ejeCargos1'),
      ejePersonas: svg.select('.ctl-ejePersonas')
    };

    var ejesCargoData = {
      eje1: {},
      eje2: {}
    };

    inicializarDataEjesCargos(data, ejes, ejesCargoData);

    var tipoGrafico = "nombre"; // posibles valores: ['nombre', 'cargo']

    // Esto se necesita para llamar a inicializar curvas
    var curvas;
    var curvasData = [];
    var color = d3.scale.category20();
    var ii = 0;


    //====================================
    // Ejes
    //====================================

    crearEjeAnios(data, svg, xScale);

    function resetSVGCanvas() {
      options.containerEl.innerHTML = svgTemplate;

      // Inicialización del svg
      svg = d3.select('svg')
        .attr("width", CHART_WIDTH)
        .attr("height", CHART_HEIGHT);


    }

    function buildxScale(data) {
      // Armar el xScale

      _.each(data.cargos, function(d) {
        if (d.fechainicioyear < primerStartingYear) {
          primerStartingYear = d.fechainicioyear;
        }
        if (ultimoEndingYear < d.fechafinyear) {
          ultimoEndingYear = d.fechafinyear;
        }
      });

      var xScale = d3.scale.linear() // DEFINE ESCALA/RANGO DE EJE X
      .domain([primerStartingYear - 2, ultimoEndingYear + 2]) // RANGO DE AÃ‘OS DE ENTRADA
      .rangeRound([
        PIXELS_H_OFFSET, // Límite izquierdo pixels
        CHART_WIDTH //Limite derecho en pixels
      ]);

      return xScale;

    }

    /*****************************************/
     // TIME SLIDER SELECTOR
    /*****************************************/

    var drag = d3.behavior.drag()
            .origin(Object)
            .on("drag", dragMove)
            .on("dragend", dragEnd);

    
    var yearMarker = svg.select('.ctl-fondo').append("g")
      .attr("class", "ctl-yearMarker grab")
      .data([{ x: xScale(ANIO_INICIAL), y: 15 }])
      .attr("transform", function (d) { return "translate(" + d.x + "," + 15 + ")"; })
      .call(drag);
   
    var yearMarkerLine = yearMarker.append('line')
            .attr('x1', 0)
            .attr('x2', 0)
            .attr('y1', 0)
            .attr('y2', 300)
            .attr('id',"linea-slider");

    yearMarker.append('rect')
            .attr('y', -10)
            .attr('x', -20)
            .attr('rx',3)
            .attr('ry',3)
            .attr('width',40)
            .attr('height',16)
            .attr('id', 'year-marker-badge')
            .attr('fill',"white");
                
    var yearMarkerLabel = yearMarker.append('text')
            .attr('y', 2)
            .attr('x', -15)
            .attr('font-size', 8)
            .attr('id', 'year-marker-label')
            .text($scope.activeYear);

            
    function dragMove(d) {
            d.x += d3.event.dx;
            d.y += d3.event.dy;
       d3.select(this).attr("transform", "translate(" + d.x + "," + 15 + ")");
       d3.select(this).attr("class","ctl-yearMarker grabbing");
        $scope.activeYear = Math.floor(xScale.invert(d.x));


      $scope.$apply(function($scope) {
          yearMarkerLabel.text($scope.activeYear)
          $scope.poderometroYear = $scope.activeYear;
          $scope.redrawPoderometro();
        });
    }

    function dragEnd(d) {
       d3.select(this).attr("class","ctl-yearMarker grab");
    }
 



    inicializarCurvas(data);

    this.update = function(newOpts) {

      try{
        ga('send', 'event', 'updateTimeline', newOpts.mostrarPor, _.map( newOpts.filtro.idPersonas.slice().sort(function(a,b){ return parseInt(a,10) - parseInt(b,10); }) , function(idper){ return idper + '-' + data.hashPersonas[idper].apellido }).join("|"));
      }catch(ex){}

      var filtro = {
        personas: null
      };

      this.options = options = _.extend(options, newOpts); //Overwrite the new ones

      tipoGrafico = options.mostrarPor || 'nombre';

      var idsPorNombre, nombres, intersection;

      if (options.filtro) {

        if (options.filtro.nombres) {


          nombres = _.filter(_.map(options.filtro.nombres.toLowerCase().split(','), function(v) {
            return v.trim();
          }), function(v) {
            return v !== "";
          });

          idsPorNombre = _.map(_.filter(data.personas, function(p) {
            var nombre = (p.nombre + ' ' + p.apellido).toLowerCase();
            for (var i = 0; i < nombres.length; i++) {
              if (nombre.indexOf(nombres[i]) > -1) {
                return true;
              }
            }
            return false;
          }), function(p) {
            return p.id;
          });

        }

        if (options.filtro.idPersonas && idsPorNombre) {
          intersection = _.intersection(options.filtro.idPersonas, idsPorNombre);
        }

        if ((options.filtro.idPersonas && options.filtro.idPersonas.length) ||
          (idsPorNombre && idsPorNombre.length)) {
          filtro.personas = _.reduce(intersection || options.filtro.idPersonas || idsPorNombre, function(memo, id) {
            memo[id] = true;
            return memo;
          }, filtro.personas || {});
        }

      }

      layout(data, ejes, groupsCargo, tipoGrafico, filtro);

    }; // this.update = function(...)...

    this.update(options);



    function inicializarCargosBloques(data) {

      var cargosContainer = svg.select('.ctl-cargos');

      cargosContainer[0][0].addEventListener('mouseover', function(e) {
        // do nothing if the target does not have the class drawnLine
        if (e.target.nodeName != "rect" && e.target.nodeName !="text") return;
        var d = d3.select(e.target.parentNode).datum();
        showTooltip(d);
        highlight(e.target.parentNode);
      });

      cargosContainer[0][0].addEventListener('mouseout', function(e) {
        // do nothing if the target does not have the class drawnLine
        if (e.target.nodeName != "rect" && e.target.nodeName !="text") return;
        var d = d3.select(e.target.parentNode).datum();
        hideTooltip();
        unhighlight(e.target.parentNode, d.nominal.nombre);
      });

      cargosContainer[0][0].addEventListener('mousemove', function(e) {
        // do nothing if the target does not have the class drawnLine
        if (e.target.nodeName != "rect" && e.target.nodeName !="text") return;
        moveTooltip(e);
      });

      
      return cargosContainer;
    }

    function highlight(el) {
      d3.select(el.childNodes[0]).transition().style('opacity', '1');
    }

    function unhighlight(el,nominalNombre) {
      if (nominalNombre == "Presidente"){
      d3.select(el.childNodes[0]).transition().style('opacity', '0.8');
      }else{
      d3.select(el.childNodes[0]).transition().style('opacity', '0.4');
      }
    }

    var tooltipEl, tooltipPreparedTemplate;

    function initTooltip() {
      tooltipEl = d3.select(options.containerEl).select('.ctl-tooltip');
      tooltipPreparedTemplate = _.template(tooltipTemplate);
    }

    function showTooltip(d) {
      tooltipEl.html(tooltipPreparedTemplate(d));

      tooltipEl.attr('class', 'ctl-tooltip ctl-' + d.nominal.tipo +  ' ' + d.territorio.nivel.split(' ')[0].toLowerCase() + ' ' +  d.nominal.nombre.split(' ')[0].toLowerCase()).style('display', 'block');
    }

    function hideTooltip() {
      tooltipEl.style('display', 'none');
    }

    function moveTooltip(event) {
      tooltipEl.style('top', event.pageY + 2 + 'px');
      tooltipEl.style('left', event.pageX + 10 + 'px');
    }

    //===================================
    // Mostrar por Cargo
    //===================================

    function mostrarPorCargo(data, ejes, groups, filtro) {

      activarEjeCargos();

      ejes.alturaMaxEjeCargo = ordenamientoPorCargo(data, filtro);

      groups.selectAll('g')
        .transition()
        .duration(TRANSITION_DURATION)
        .attr('opacity', 1)
        .attr('transform', function(d) {
          var x = xScale(d.fechainicioyear);
          var y = (d.__layout.cargo.altura * ALTO_BLOQUES || 0) + OFFSET_Y;
          return 'translate(' + x + ',' + y + ')';
        });

      ejes.ejeCargos0
        .selectAll('g')
        .transition()
        .duration(TRANSITION_DURATION)
        .attr('opacity', function(d) {
          return d.display ? 1 : 0;
        })
        .attr('transform', function(d) {
          var x = 0;
          var y = d.display ? (d.altura * ALTO_BLOQUES || 0) + OFFSET_Y : ALTURA_OCULTAMIENTO;
          return 'translate(' + x + ',' + y + ')';
        });

      ejes.ejeCargos1
        .selectAll('g')
        .transition()
        .duration(TRANSITION_DURATION)
        .attr('opacity', function(d) {
          return d.display ? 1 : 0;
        })
        .attr('transform', function(d) {
          var x = PIXELS_H_OFFSET;
          var y = d.display ? (d.altura * ALTO_BLOQUES || 0) + OFFSET_Y : ALTURA_OCULTAMIENTO;
          return 'translate(' + x + ',' + y + ')';
        });

      actualizarLayoutCurvas();

    }


    function inicializarDataEjesCargos(data, ejes, ejesCargoData) {

      _.each(data.cargos, function(d) {
        //TODO: Antes no pasa esto
        if (!d.nominal) return ;
        ejesCargoData.eje1[d.cargo_nominal_id] = {
          altura: 0,
          nombre: d.nominal.nombre,
          display: false
        };

        ejesCargoData.eje2[d.cargo_nominal_id + ' | ' + d.territorio_id] = {
          altura: 0,
          nombre: d.territorio.nombre,
          display: false,
          parentEje: ejesCargoData.eje1[d.cargo_nominal_id]
        };

      });

      ejes.ejeCargos0
        .selectAll('g')
        .data(_.map(ejesCargoData.eje1, function(d) {
          return d;
        }))
        .enter()
        .append('g')
        .attr('opacity', 0)
        .each(function(d) {

          var g = d3.select(this);

          g.append('text')
            .attr('y', ALTO_BLOQUES*0.6)
            .attr('x', 5)
            .text(function(d) {
              return d.nombre;
            });

          g.append("line")
            .attr("x1", 0)
            .attr("y1", -2)
            .attr("x2", CHART_WIDTH)
            .attr("y2", -2)
            .attr("stroke", "#CCC");

        });

      ejes.ejeCargos1
        .selectAll('g')
        .data(_.map(ejesCargoData.eje2, function(d) {
          return d;
        }))
        .enter()
        .append('g')
        .attr('opacity', 0)
        .each(function(d) {

          var g = d3.select(this);

          g.append('text')
            .attr('y', ALTO_BLOQUES*0.6)
            .text(function(d) {
              return d.nombre;
            });

        });


    }

    function ordenamientoPorCargo(data, filtro) {

      var alturaMaxReturn = 0;

      //Reset las alturas de los ejes
      _.each(ejesCargoData.eje1, function(d) {
        d.altura = 0;
        d.display = 0;
      });

      _.each(ejesCargoData.eje2, function(d) {
        d.altura = 0;
        d.display = 0;
      });

      //Reset todas las alturas de los cargos
      _.each(data.cargos, function(item) {
        item.__layout.cargo = {
          display: filtrarCargo(item, filtro),
          altura: 0
        };
      });

      var filteredData = _.filter(data.cargos, function(item) {
        return item.__layout.cargo.display;
      });

      updateBloques(filteredData);

      var alturaCargoNominal = 0;

      //Agrupar por cargo nominal
      var agrupado = _.sortBy(
        _.map(
          _.groupBy(filteredData,
            function(cargo) {
              return cargo.cargo_nominal_id + ' | ' + cargo.territorio_id;
            }), function(el, key) {
            return {
              nombre: key,
              cargos: el
            };
          }), 'nombre');

      _.each(agrupado, function(item) {

        var cargonominal = item.nombre;
        var cargos = item.cargos;

        var procesados = [];

        ordenarPorNombreyFechaInicioYear(cargos);

        _.each(cargos, function(cargo) {

          cargo.altura = -1;
          var colision, i;

          do {
            colision = false;
            cargo.altura += 1;
            for (i = 0; i < procesados.length; i++) {
              var cargoProcesado = procesados[i];
              if (cargoProcesado.altura == cargo.altura && cargoProcesado.fechainicioyear < cargo.fechafinyear && cargoProcesado.fechafinyear > cargo.fechainicioyear) {
                colision = true;
              }
            }
          } while (colision);

          procesados.push(cargo);

          cargo.__layout.cargo.altura = alturaCargoNominal + cargo.altura; //Este es el offset Y total

          //Altura del label del eje2
          var keyEje2 = cargo.cargo_nominal_id + ' | ' + cargo.territorio_id;
          if (!ejesCargoData.eje2[keyEje2].display) {
            ejesCargoData.eje2[keyEje2].display = true;
            ejesCargoData.eje2[keyEje2].altura = cargo.__layout.cargo.altura;
            alturaMaxReturn = cargo.__layout.cargo.altura;
            if (!ejesCargoData.eje1[cargo.cargo_nominal_id].display) {
              ejesCargoData.eje1[cargo.cargo_nominal_id].display = true;
              ejesCargoData.eje1[cargo.cargo_nominal_id].altura = cargo.__layout.cargo.altura;
            }
          }

        });

        alturaCargoNominal++;

      });

      return alturaMaxReturn;
    }

    function strCmp(s1, s2) {
      if (s1 == s2) {
        return 0;
      } else {
        if (s1 > s2) {
          return 1;
        } else {
          return -1;
        }
      }
    }

    function ordenarPorNombreyFechaInicioYear(cargos) {
      cargos.sort(function(a, b) {
        return strCmp(a.nombre, b.nombre) || strCmp(a.territorio, b.territorio) || (a.fechainicioyear - b.fechainicioyear);
      });
    }


    //==============================
    //Mostrar por nombre
    //==============================

    function mostrarPorNombre(data, ejes, groups, filtro) {

      var nombresAMostrar = {};

      //Filtrar los cargos
      _.each(data.cargos, function(d) {
        var display = filtrarCargo(d, filtro);
        if (display) nombresAMostrar[d.persona_id] = 1;
        d.__layout.nombre = {
          display: display
        };
      });

      //Acá va el ordenamiento de las personas
      var listaPersonas = _.sortBy(_.map(nombresAMostrar, function(value, key) {
        return key;
      }), function(persona_id) {
        return data.hashPersonas[persona_id].nombre + ' ' + data.hashPersonas[persona_id].apellido;
      });

      var cargosAMostrar = _.filter(data.cargos, function(d) {
        return d.__layout.nombre.display;
      });

      updateBloques(cargosAMostrar);

      var cargosPorPersonas = _.groupBy(cargosAMostrar, function(c) {
        return c.persona_id;
      });

      var alturasPersona = {};

      var alturaTotal = 0;
      _.each(listaPersonas, function(persona_id, ix) {


        alturasPersona[persona_id] = 0;
        var currentAltura = 0;
        var cargos = cargosPorPersonas[persona_id];
        if (!cargos) return;

        cargos.sort(function(a, b) {
          return a.fechainicioyear - b.fechainicioyear;
        });

        var cargo = cargos.shift();
        var processedCargos = [];
        var i;

        while (cargo) {
          var collision = false;
          for (i = 0; i < processedCargos.length; i++) {
            var cargoProcesado = processedCargos[i];
            if (cargoProcesado.fechafinyear > cargo.fechainicioyear) {
              collision = true;
            }
          }

          if (collision) {
            currentAltura++;
          }
          cargo.altura = currentAltura;
          processedCargos.push(cargo);
          cargo = cargos.shift();
        }

        alturasPersona[persona_id] = currentAltura;

      });

      activarEjePersonas();

      var personasToAltura = {};
      var acumH = 0;

      _.each(listaPersonas, function(persona_id) {
        personasToAltura[persona_id] = acumH;
        acumH += (1 + alturasPersona[persona_id]);
      });

      //updateEjePersonas(ejes, personasToAltura);

      // listaPersonas

      var itemsEje = ejes.ejePersonas.selectAll('g').data(listaPersonas, function(d){return d});

      itemsEje.enter().append('g')
        .each(function(d, ix) {

          var g = d3.select(this);

          g.append('text')
            .attr('y', ALTO_BLOQUES*0.6)
            .attr('x', 5)
            .text(function(d) {
              return data.hashPersonas[d].nombre + ' ' + data.hashPersonas[d].apellido;
            });

          g.append("line")
            .attr("x1", 0)
            .attr("y1", -2)
            .attr("x2", CHART_WIDTH)
            .attr("y2", -2)
            .attr("stroke", "#CCC");
        });

      itemsEje.exit().remove();

      ejes.ejePersonas.selectAll('g')
        .attr('transform', function(d) {
          var x = 0;
          var y = personasToAltura[d] !== undefined ? (personasToAltura[d] * ALTO_BLOQUES || 0) + OFFSET_Y : ALTURA_OCULTAMIENTO;
          return 'translate(' + x + ',' + y + ')';
        })
        .attr('opacity', 1);

      ejes.alturaMaxPersonas = acumH;

      groupsCargo.selectAll('g')
        .transition()
        .duration(TRANSITION_DURATION)
        .attr('opacity',1)
        .attr('transform', function(d) {
          var x = xScale(d.fechainicioyear);
          var y = ((personasToAltura[d.persona_id] + d.altura) * ALTO_BLOQUES || 0) + OFFSET_Y;
          return 'translate(' + x + ',' + y + ')';
        });

      ocultarCurvas();
    }

    function updateBloques(filteredCargos){

      var theRef = groupsCargo.selectAll('g').data(filteredCargos, function(d) { return d.id; });

      //Remove old nodes
      theRef.exit().remove();

      //Add new nodes
      theRef.enter()
        .append('g')
        .attr('opacity', 0)
        .each(function(d) {

          var g = d3.select(this);
          var anchoBox = Math.max(0, xScale(d.fechafinyear) - xScale(d.fechainicioyear) - 2);
          var fullName = d.persona.nombre + ' ' + d.persona.apellido;
          g.attr("class","bloques")
          g.append('rect')
            .attr('rx',2)
            .attr('ry',2)
            .attr('width', anchoBox)
            .attr('height', ALTO_BLOQUES - 4)
            .attr('class', function(d) {
              return 'ctl-' + d.nominal.tipo + ' '  + d.territorio.nivel.split(' ')[0].toLowerCase() + ' ' +  d.nominal.nombre.split(' ')[0].toLowerCase();
            })
            .style('opacity', function(d) {
              return ((d.nominal.nombre == "Presidente") ? 0.8 : 0.4);
            });

          g.append('text')
            .attr('y', ALTO_BLOQUES*0.6)
            .attr('x', 2)
            .attr('font-size', ALTO_BLOQUES*0.5)
            .attr('class', 'ctl-nombre')
            .text(function(d) {
              if(fullName.length > (anchoBox/5)) {
                  return d.persona.nombre.substring(0,Math.floor(anchoBox/5));
                }else {
                  return d.persona.nombre + ' ' + d.persona.apellido;
              }
              
            });

          g.append('text')
            .attr('opacity',0)
            .attr('y', ALTO_BLOQUES*0.6)
            .attr('x', 2)
            .attr('font-size', ALTO_BLOQUES*0.5)
            .attr('class', 'ctl-cargo')
            .text(function(d) {
              if(d.nominal.nombre.length > (anchoBox/5)) {
                  return d.nominal.nombre.substring(0,Math.floor(anchoBox/5));
                }else {
                  return d.nominal.nombre;
              }
              
            });


        });
    }

    //================================
    // Layout
    //================================

    function layout(data, ejes, groups, tipoGrafico, filtro) {

      var altura = CHART_HEIGHT;

      if (tipoGrafico == "nombre") {
          mostrarPorNombre(data, ejes, groups, filtro);
          altura = ejes.alturaMaxPersonas * ALTO_BLOQUES + 100;
      } else if (tipoGrafico == "cargo"){
          //Tipo Gráfico: "cargo"
          mostrarPorCargo(data, ejes, groups, filtro);
          altura = ejes.alturaMaxEjeCargo * ALTO_BLOQUES + 100;
      }
      //Setear la altura
      svg = d3.select('svg')
      .attr("height", altura);

    }


    function filtrarCargo(cargo, filtro) {
      if(filtro.personas){
        return !!filtro.personas[cargo.persona_id];
      }else{
        return false;
      }
    }

    //================================
    //Curvas
    //================================

    function inicializarCurvas(data) {

      var personas = _.groupBy(data.cargos, function(d) {
        return d.persona_id;
      });

      _.each(personas, function(d, index) {
        var i;
        var cargosDeLaPersona = _.sortBy(d, 'fechainicioyear');


        for (i = 0; i < cargosDeLaPersona.length - 1; i++) {
          curvasData.push({
            izq: cargosDeLaPersona[i],
            der: cargosDeLaPersona[i + 1],
            colorStroke: color(index)
          });
        }

      });

      curvas = svg.select('.ctl-curvas')
        .selectAll('path')
        .data(curvasData)
        .enter()
        .append('path')
        .attr('opacity', 0)
        .attr('fill', 'none')
        .attr('stroke', 'red')
        .attr('stroke-width', '2px');

    }

    function actualizarLayoutCurvas() {
      var controlLenght = 20;
      var OFFSET_Y_CURVAS = 10;

      curvas
        .attr('opacity', 1)
        .transition()
        .duration(TRANSITION_DURATION)
        .attr('d', function(d) {

          var fromX = xScale(d.izq.fechafinyear) - 2;
          var fromY = d.izq.__layout.cargo.display ? (d.izq.__layout.cargo.altura * ALTO_BLOQUES || 0) + OFFSET_Y + OFFSET_Y_CURVAS : ALTURA_OCULTAMIENTO;

          var control1X = fromX + controlLenght;
          var control1Y = fromY;

          var toX = xScale(d.der.fechainicioyear);
          var toY = d.der.__layout.cargo.display ? (d.der.__layout.cargo.altura * ALTO_BLOQUES || 0) + OFFSET_Y + OFFSET_Y_CURVAS : ALTURA_OCULTAMIENTO;

          var contorl2X = toX - controlLenght;
          var control2Y = toY;

          //"M100,200 C10,10 400,10 400,200";
          return "M" + fromX + "," + fromY + " C" + control1X + "," + control1Y + " " + contorl2X + "," + control2Y + " " + toX + "," + toY;

        })
        .attr('stroke', function(d) {
          return d.colorStroke;
        });
    }

    function ocultarCurvas() {
      curvas.attr('opacity', 0);
    }

    //================================
    //Eje años
    //================================

    function crearEjeAnios(data, svg, xScale) {

      var anios = getAniosMasUsados(data, 10);
      anios.push(primerStartingYear, ultimoEndingYear);
      anios.push(1976, 1983);
      
      var xAxis = d3.svg.axis()
        .scale(xScale)
        .orient("bottom")
        .tickFormat(d3.format("0"))
        .tickValues(anios);

      svg.select('.ctl-fondo').append("g").attr("class", "ctl-axis")
        .attr("transform", "translate(0," + EJE_ANIOS_OFFSET_Y + ")")
        .call(xAxis);
      
    }

    function getAniosMasUsados(data, corte) {

      var ret;
      var toOrder = [];
      var i, count = {};
      var anios = data.cargos.map(function(cargo) {
        return parseInt(cargo.fechainicioyear, 10);
      });

      anios.concat(data.cargos.map(function(cargo) {
        return parseInt(cargo.fechafinyear, 10);
      }));

      for (i = 0; i < anios.length; i++) {
        if (count[anios[i]]) {
          count[anios[i]]++;
        } else {
          count[anios[i]] = 1;
        }
      }

      for (anios in count) {
        toOrder.push({
          anio: anios,
          veces: count[anios]
        });
      }

      ret = toOrder.sort(function(item1, item2) {
        return item2.veces - item1.veces;
      }).slice(0, corte);

      return ret.map(function(item) {
        return item.anio;
      }).sort();
    }

    //==============================
    //Ejes
    //==============================

    function activarEjePersonas() {
      ejes.ejePersonas.transition().duration(TRANSITION_DURATION).attr('opacity', 1);
      ejes.ejeCargos0.transition().duration(TRANSITION_DURATION).attr('opacity', 0);
      ejes.ejeCargos1.transition().duration(TRANSITION_DURATION).attr('opacity', 0);
      ejes.ejeCargos1.transition().duration(TRANSITION_DURATION).attr('opacity', 0);
      d3.selectAll(".ctl-nombre").transition().duration(TRANSITION_DURATION).attr('opacity', 0);
      d3.selectAll(".ctl-cargo").transition().duration(TRANSITION_DURATION).attr('opacity', 1);

    }

    function activarEjeCargos() {
      ejes.ejePersonas.transition().duration(TRANSITION_DURATION).attr('opacity', 0);
      ejes.ejeCargos0.transition().duration(TRANSITION_DURATION).attr('opacity', 1);
      ejes.ejeCargos1.transition().duration(TRANSITION_DURATION).attr('opacity', 1);
      d3.selectAll(".ctl-nombre").transition().duration(TRANSITION_DURATION).attr('opacity', 1);
      d3.selectAll(".ctl-cargo").transition().duration(TRANSITION_DURATION).attr('opacity', 0);

    }

  }; // Constructor

  window.CargografiasTimeline = CargografiasTimeline;

  //Funciones que no necesitan a la instancia;

  function normalizarDatos(origData) {

    // en realidad, desnormaliza los datos.
    var data = {
      personas: copyArr(origData.personas),
      cargosnominales: copyArr(origData.cargosnominales),
      partidos: copyArr(origData.partidos),
      territorios: copyArr(origData.territorios),
      cargos: copyArr(_.filter(origData.cargos, function(c) {
        return parseInt(c.fechainicio, 10) > 1970;
      })),
    };
    //data.cargos = data.cargos.slice(0,500);

    data.hashPersonas = _.reduce(data.personas, function(memo, p) {
      memo[p.id] = p;
      return memo;
    }, {});
    data.hashCargosNominales = _.reduce(data.cargosnominales, function(memo, p) {
      memo[p.id] = p;
      return memo;
    }, {});
    data.hashPartidos = _.reduce(data.partidos, function(memo, p) {
      memo[p.id] = p;
      return memo;
    }, {});
    data.hashTerritorios = _.reduce(data.territorios, function(memo, p) {
      memo[p.id] = p;
      return memo;
    }, {});

    _.each(data.cargos, function(cargo) {
      cargo.fechainicioyear = parseInt((cargo.fechainicio || '').substr(0, 4) || "", 10);
      cargo.fechafinyear = parseInt((cargo.fechafin || '').substr(0, 4) || "", 10) || thisYear;
      cargo.__layout = {}; //Esto va a tener la info de layout
      cargo.persona = data.hashPersonas[cargo.persona_id];
      cargo.nominal = data.hashCargosNominales[cargo.cargo_nominal_id];
      cargo.partido = data.hashPartidos[cargo.partido_id];
      cargo.territorio = data.hashTerritorios[cargo.territorio_id];
    });



    return data;

  }

  function copyArr(arr) {
    var i, newArr = [];
    for (i = 0; i < arr.length; i++) {
      newArr.push(_.clone(arr[i]));
    }
    return newArr;
  }

})(document, window, d3, _, jQuery);



var defaultDiacriticsRemovalMap = [
    {'base':'A', 'letters':/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g},
    {'base':'AA','letters':/[\uA732]/g},
    {'base':'AE','letters':/[\u00C6\u01FC\u01E2]/g},
    {'base':'AO','letters':/[\uA734]/g},
    {'base':'AU','letters':/[\uA736]/g},
    {'base':'AV','letters':/[\uA738\uA73A]/g},
    {'base':'AY','letters':/[\uA73C]/g},
    {'base':'B', 'letters':/[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g},
    {'base':'C', 'letters':/[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g},
    {'base':'D', 'letters':/[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g},
    {'base':'DZ','letters':/[\u01F1\u01C4]/g},
    {'base':'Dz','letters':/[\u01F2\u01C5]/g},
    {'base':'E', 'letters':/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g},
    {'base':'F', 'letters':/[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g},
    {'base':'G', 'letters':/[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g},
    {'base':'H', 'letters':/[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g},
    {'base':'I', 'letters':/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g},
    {'base':'J', 'letters':/[\u004A\u24BF\uFF2A\u0134\u0248]/g},
    {'base':'K', 'letters':/[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g},
    {'base':'L', 'letters':/[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g},
    {'base':'LJ','letters':/[\u01C7]/g},
    {'base':'Lj','letters':/[\u01C8]/g},
    {'base':'M', 'letters':/[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g},
    {'base':'N', 'letters':/[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g},
    {'base':'NJ','letters':/[\u01CA]/g},
    {'base':'Nj','letters':/[\u01CB]/g},
    {'base':'O', 'letters':/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g},
    {'base':'OI','letters':/[\u01A2]/g},
    {'base':'OO','letters':/[\uA74E]/g},
    {'base':'OU','letters':/[\u0222]/g},
    {'base':'P', 'letters':/[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g},
    {'base':'Q', 'letters':/[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g},
    {'base':'R', 'letters':/[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g},
    {'base':'S', 'letters':/[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g},
    {'base':'T', 'letters':/[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g},
    {'base':'TZ','letters':/[\uA728]/g},
    {'base':'U', 'letters':/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g},
    {'base':'V', 'letters':/[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g},
    {'base':'VY','letters':/[\uA760]/g},
    {'base':'W', 'letters':/[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g},
    {'base':'X', 'letters':/[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g},
    {'base':'Y', 'letters':/[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g},
    {'base':'Z', 'letters':/[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g},
    {'base':'a', 'letters':/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
    {'base':'aa','letters':/[\uA733]/g},
    {'base':'ae','letters':/[\u00E6\u01FD\u01E3]/g},
    {'base':'ao','letters':/[\uA735]/g},
    {'base':'au','letters':/[\uA737]/g},
    {'base':'av','letters':/[\uA739\uA73B]/g},
    {'base':'ay','letters':/[\uA73D]/g},
    {'base':'b', 'letters':/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
    {'base':'c', 'letters':/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
    {'base':'d', 'letters':/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
    {'base':'dz','letters':/[\u01F3\u01C6]/g},
    {'base':'e', 'letters':/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
    {'base':'f', 'letters':/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
    {'base':'g', 'letters':/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
    {'base':'h', 'letters':/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
    {'base':'hv','letters':/[\u0195]/g},
    {'base':'i', 'letters':/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
    {'base':'j', 'letters':/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
    {'base':'k', 'letters':/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
    {'base':'l', 'letters':/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
    {'base':'lj','letters':/[\u01C9]/g},
    {'base':'m', 'letters':/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
    {'base':'n', 'letters':/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
    {'base':'nj','letters':/[\u01CC]/g},
    {'base':'o', 'letters':/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
    {'base':'oi','letters':/[\u01A3]/g},
    {'base':'ou','letters':/[\u0223]/g},
    {'base':'oo','letters':/[\uA74F]/g},
    {'base':'p','letters':/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
    {'base':'q','letters':/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
    {'base':'r','letters':/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
    {'base':'s','letters':/[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
    {'base':'t','letters':/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
    {'base':'tz','letters':/[\uA729]/g},
    {'base':'u','letters':/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
    {'base':'v','letters':/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
    {'base':'vy','letters':/[\uA761]/g},
    {'base':'w','letters':/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
    {'base':'x','letters':/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
    {'base':'y','letters':/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
    {'base':'z','letters':/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g}
];
var changes;
function removeDiacritics (str) {
    if(!changes) {
        changes = defaultDiacriticsRemovalMap;
    }
    for(var i=0; i<changes.length; i++) {
        str = str.replace(changes[i].letters, changes[i].base);
    }
    return str;
}