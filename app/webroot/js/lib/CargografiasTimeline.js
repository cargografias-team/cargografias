(function(document, window, d3, _, $) {
  "use strict";

  var thisYear = (new Date()).getFullYear();

  var tooltipTemplate =
    '<b><%- persona.nombre + " " + persona.apellido %></b><br>' +
    '<%- nominal.tipo %><br> ' +
    '<span class="ctl-detalles">' +
    '<%- fechainicioyear %> - <%- fechafinyear %><br>' +
    '<%- territorio.nombre %>' +
    '</span>';

  var svgTemplate =
    '<svg>' +
    '<g class="ctl-ejeCargos0"></g>' +
    '<g class="ctl-ejeCargos1"></g>' +
    '<g class="ctl-ejePersonas"></g>' +
    '<g class="ctl-cargos"></g>' +
    '<g class="ctl-curvas"></g>' +
    '</svg>' +
    '<div class="ctl-tooltip"></div>';

  window.CargografiasTimeline = function(options) {

    this.options = options;

    // "Constantes"
    var CHART_WIDTH = $(options.containerEl).width() - 20 ;
    var CHART_HEIGHT = 0; //Not a constant anymore, hay que renombrar

    var PIXELS_PER_YEAR = 20;
    var ALTO_BLOQUES = 30; //Alto de los bloques
    var PIXELS_H_OFFSET = 200;
    var TRANSITION_DURATION = 1500;
    var OFFSET_Y = 40; // USado para mover verticalmente los  blques y el eje vertical
    var EJE_ANIOS_OFFSET_Y = 8;

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

    //*** CIRCULITO QUE MARCA EL AÑO CUANDO EL MOUSE SE MUEVE 
    var yearMarker = svg.append("g")
      .attr("class", "ctl-yearMarker")
      .style("display", "none");

    yearMarker.append("circle")
      .attr("r", 5);

    svg
      .on("mouseover", function() {
        yearMarker.style("display", null);
      })
      .on("mouseout", function() {
        yearMarker.style("display", "none");
      })
      .on("mousemove", mousemove);

    function mousemove() {
      /*jshint validthis:true */
      yearMarker.attr("transform", "translate(" + d3.mouse(this)[0] + ", " + EJE_ANIOS_OFFSET_Y + ")");
    }

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
        unhighlight(e.target.parentNode);
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

    function unhighlight(el) {
      d3.select(el.childNodes[0]).transition().style('opacity', '0.5');
    }

    var tooltipEl, tooltipPreparedTemplate;

    function initTooltip() {
      tooltipEl = d3.select(options.containerEl).select('.ctl-tooltip');
      tooltipPreparedTemplate = _.template(tooltipTemplate);
    }

    function showTooltip(d) {
      tooltipEl.html(tooltipPreparedTemplate(d));
      tooltipEl.attr('class', 'ctl-tooltip ctl-' + d.nominal.tipo).style('display', 'block');
    }

    function hideTooltip() {
      tooltipEl.style('display', 'none');
    }

    function moveTooltip(event) {
      tooltipEl.style('top', event.pageY + 2 + 'px');
      tooltipEl.style('left', event.pageX + 2 + 'px');
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
        .attr('opacity', function(d) {
          return d.display ? 1 : 0;
        })
        .attr('transform', function(d) {
          var x = 200;
          var y = d.display ? (d.altura * ALTO_BLOQUES || 0) + OFFSET_Y : ALTURA_OCULTAMIENTO;
          return 'translate(' + x + ',' + y + ')';
        });

      actualizarLayoutCurvas();

    }


    function inicializarDataEjesCargos(data, ejes, ejesCargoData) {

      _.each(data.cargos, function(d) {

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
            .attr('y', 18)
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
            .attr('y', 18)
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
            .attr('y', 18)
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

          g.append('rect')
            .attr('width', Math.max(0, xScale(d.fechafinyear) - xScale(d.fechainicioyear) - 2))
            .attr('height', ALTO_BLOQUES - 4)
            .attr('class', function(d) {
              return 'ctl-' + d.nominal.tipo;
            });

          g.append('text')
            .attr('y', 10)
            .attr('x', 2)
            .attr('font-size', 8)
            .attr('class', 'ctl-cargo')
            .text(function(d) {
              return d.nominal.nombre;
            });

          g.append('text')
            .attr('y', 20)
            .attr('x', 2)
            .attr('font-size', 8)
            .attr('class', 'ctl-nombre')
            .text(function(d) {
              return d.persona.nombre + ' ' + d.persona.apellido;
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
      } else {
        //Tipo Gráfico: "cargo"
        mostrarPorCargo(data, ejes, groups, filtro);
        altura = ejes.alturaMaxEjeCargo * ALTO_BLOQUES + 100;
      }
      //Setear la altura
      svg = d3.select('svg').attr("height", altura);

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

      svg.append("g").attr("class", "ctl-axis")
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
      ejes.ejePersonas.transition().attr('opacity', 1);
      ejes.ejeCargos0.transition().attr('opacity', 0);
      ejes.ejeCargos1.transition().attr('opacity', 0);
    }

    function activarEjeCargos() {
      ejes.ejePersonas.transition().attr('opacity', 0);
      ejes.ejeCargos0.transition().attr('opacity', 1);
      ejes.ejeCargos1.transition().attr('opacity', 1);
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
