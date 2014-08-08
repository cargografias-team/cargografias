var App;

;(function(global, document, tabletop){

    "use strict";

    App = global.cargografia = global.cargografia || {};

    // Inicia: Usa tabletop para traer data. 
    //Cuando termina de cargar los datos, llama a la funcion renderD3();
       App.init = function () {
        var dataCargos = [];
        if(localStorage && localStorage.cargoData){
            dataCargos = JSON.parse(localStorage.cargoData);
            App.renderD3(dataCargos);
        }else{
          tabletop.init(
            {
                key: "https://docs.google.com/spreadsheet/pub?key=0Av8QEY2w-qTYdFViaG5ULTlUQ1Q4M3AxS1NvcWI0UlE&single=true&gid=0&output=html",
                callback: function(data){
                    if(localStorage){
                        localStorage.cargoData = JSON.stringify(data);
                    }
                    App.renderD3(data);
                },
                simpleSheet: true 
            });
        }
    };

    //PreFilterData: ACA AJUSTA LOS DATOS QUE VIENEN DE LA SPREADSHET Y LOS TOQUETEA UN POCO, 
    //y le pone los nombres correctos
    App.preFilterData = function(data) { 
        var resp = {}, cargoID = 0, lastName = "",
            primerStartingYear = 2000, 
            ultimoEndingYear = 2000,
            anioMasUsados = App.getAniosMasUsados(data,20),
            totalPoliticos = 0,
            totalRenglones = 0,
            anioDeFinAnterior = 9000, //muy alto
			offsetY = 0;

        data.forEach(function(e,i){
            var startTime = Number(e['fechainicioyear']),
                endTime = Number(e['fechafinyear']); 

            if(lastName != e['nombre']){
                cargoID ++;
            }
            lastName = e['nombre'];
            if(isNaN(endTime)){
                endTime = 2013;
            }

            if(!resp[cargoID]) {
                anioDeFinAnterior = false; //le pongo false porque es el primero.
				offsetY = 0;
                totalPoliticos++;
                totalRenglones++;
                resp[cargoID] = {"nombre":e['nombre'], "cargoID":cargoID, "lineas":1, "cargos":[]};
				
            } 

            //Es un puesto que interrumpe al anterior y necesita nuevo renglon?
			if(i && Number(data[i-1]['duracioncargo']) && ((Number(data[i-1]['fechainicioyear'])+Number(data[i-1]['duracioncargo'])) > e['fechainicioyear'])) {
                offsetY++;
                totalRenglones++;
                resp[cargoID].lineas++;
            }


            //Verifico limites
            primerStartingYear = (primerStartingYear>startTime)?startTime:primerStartingYear;
            ultimoEndingYear = (ultimoEndingYear<endTime)?endTime:ultimoEndingYear;

            var unit = {
                nombre: e['nombre'], 
                desde: Number(e['fechainicioyear']),
                hasta: endTime,
                cargoID: cargoID,
                cargo: e['cargonominal'],
                territorio: e['territorio'],
                cargoTipo: e['cargotipo'],
                dura: Number(e['duracioncargo']),
				cargoExt: e['cargoext'],
				offsetY: offsetY
            };

            resp[cargoID].cargos.push(unit);
        });
        return {data:resp,
                limiteInferior:primerStartingYear,
                limiteSuperior:ultimoEndingYear,
                anios:anioMasUsados,
                totalPoliticos:totalPoliticos,
                totalRenglones:totalRenglones
                };
      }

    // ESTA ES LA FUNC. QUE REALMENTE DIBUJA
    App.renderD3 = function (data) { 
        var newData = App.preFilterData(data), //pre-procesa la data, retorna un objeto con varios datos
            marginSVG = 10,
            itemHeight = 25,
            spacingY = 20,
            svgWidth = 1600,
			alinearNombresIzq = 0,
			lineasDivisorias = 1,
            svgHeight = newData.totalRenglones * (itemHeight + spacingY + 1 ),
            primerStartingYear = newData.limiteInferior,
            ultimoEndingYear = newData.limiteSuperior,
            lineasPorPolitico = [];
        
        var xScale = d3.scale.linear()  // DEFINE ESCALA/RANGO DE EJE X
                    .domain([primerStartingYear-5,ultimoEndingYear+5]) // RANGO DE AÑOS DE ENTRADA
                    .rangeRound([1,svgWidth]); // ANCHO MAXIMO DEL CUADRO EN PIXELES
            
            
        //*** ARMADO DEL SVG            
        var svg = d3.select("#timeline").append("svg")  
                    .attr("width", svgWidth)
                    .attr("height", svgHeight);

        //*** CIRCULITO QUE MARCA EL AÑO CUANDO EL MOUSE SE MUEVE 
        var yearMarker = svg.append("g")
                            .attr("class", "yearMarker")
                            .style("display","none");

        yearMarker.append("circle")
                    .attr("r", 5);

        //Crea el tooltip            
        var tooltip = d3.select("body").append("div")   
                        .attr("id", "tooltip")               
                        .style("opacity", 0);

        // COSAS QUE PASAN CUANDO EL MOUSE SE MUEVE 
        // Marker de los años y tooltip
        svg.on("mouseover", function() { yearMarker.style("display", null); })
            .on("mouseout", function() { yearMarker.style("display", "none"); })
            .on("mousemove", mousemove);

        function mousemove() {
            yearMarker.attr("transform", "translate(" + d3.mouse(this)[0] + ","+(marginSVG+0.5)+")");
            tooltip.style("left", (d3.event.pageX) + "px").style("top", (d3.event.pageY - 28) + "px");
        }

        var heightAcumulado = 0;

        // COMIENZA LA CONSTRUCCION DEL CHART 
        var itemsPolitico = svg.selectAll("g")
                            .data(d3.entries(newData.data))             
                            .enter()
                            .append("g")
                            .attr("class", "politico")
                            .attr("id",  function(d, i) { return "politico-"+d.value.cargoID;})
                            .each(function(d,i){ //procesa cada politico

                                var politico = svg.select('#politico-'+d.value.cargoID);//Grupo de cada político

                                //Agrego la línea
								if(lineasDivisorias){
                                    politico
                                    .append("line")
                                    .attr("x1",0)
                                    .attr("y1",d.value.lineas * itemHeight + (spacingY/2) )
                                    .attr("x2",svgWidth)
                                    .attr("y2",d.value.lineas * itemHeight + (spacingY/2) )
                                    .attr("stroke","#CCC");
								}

                                //Agrego el nombre del político
                              var nombrePol = politico.append("text")
                                .text(d.value.nombre)
                                .attr("class","nombreLabel")
								.attr("y", 18);
								
								if(alinearNombresIzq){
									nombrePol.style("text-anchor","end")
			                                .attr("x", xScale(Number(d.value.cargos[0].desde))-15)
								} else {
									nombrePol.attr("x",5)
								}

                                //Agrego el contenedor de cargos
                                var cargosContainer = politico
                                                    .append("g")
                                                    .attr("class", "cargos-container");

                                //Lleno el contenedor con cargos
                                var cargos = cargosContainer
                                    .selectAll("g")
                                    .data(d.value.cargos)
                                    .enter()
                                    .append("g");
					   

								cargos.attr("transform", function(d){

                                    if(d.offsetY>0){
                                        //do something
                                    }
									return ("translate(" + xScale(d.desde) + ","+d.offsetY*itemHeight+")");
								});
								
								var rectangleCargo = cargos.append("rect")
                                    .attr("class", function(d, i) {
                                            if(d.cargo.indexOf("presidente")){
                                                return d.cargoTipo;
                                            }else{
                                                return "presidente " + d.cargoTipo;
                                            }
                                        })
                                    
                                    .attr("height", itemHeight)
                                    .attr("width", function(d, i) {
                                            var finalWidth = xScale(d.hasta) - xScale(d.desde);
                                            if (finalWidth > 0){
                                                return finalWidth-1;                                    
                                            } else {
                                                return 3;
                                            }
                                    	})

								var rectangleVacio = cargos.append("rect")
                                    .attr("class", "vacio")
                                    .attr("height", itemHeight)
									.attr("width", function(d, i) {
                                            var finalWidth = xScale(d.desde+d.dura) - xScale(d.hasta);
                                                return finalWidth;                                    
                                    	})
                                    .attr("x", function(d, i) {
                                            var finalWidth = xScale(d.hasta) - xScale(d.desde);
                                            if (finalWidth > 0){
                                                return finalWidth-1;                                    
                                            } else {
                                                return 3;
                                            }
                                    	})

                                //Agrego titulo al rect - no anda
								var inLabels = cargos.append("text")
                                    .text(function(d){return d.cargo;})
                                    .attr("x", 3)
                                    .attr("y", 10)
                                    .attr("class","miniLabels")
                                
                                //Agrego subtitulo al rect - no anda
                               	var inLabelitos = cargos.append("text")
                                    .text(function(d){return d.territorio;})
                                    .attr("x", 3)
                                    .attr("y", 19)
                                    .attr("class","miniLabels microLabels")

                                //Agrego funcionalidad del tooltip
                                cargos.on("mouseover", function(d) {
										var innerHTML = "<b>"+d.nombre+"</b><br/>"+d.cargo.toUpperCase();
										if(d.cargoExt){ innerHTML += "<br>"+d.cargoExt};									
										innerHTML += "<span class=fecha><br/>"+d.desde+" - "+d.hasta +"<br/>"+d.territorio;        
										tooltip.attr("class",d.cargoTipo)
                                        tooltip.transition()        
                                            .duration(100)      
                                            .style("opacity", .9)
										tooltip.html(innerHTML)      
											.style("left", (d3.event.pageX) + "px")     
                                            .style("top", (d3.event.pageY - 28) + "px");    
                                        })
                                    .on("mouseout", function(d) {       
                                        tooltip.transition()        
                                            .duration(200)      
                                            .style("opacity", 0);   
                                    });
                            })
                            .attr("transform", function(d, i) {
                                //Cálculo de la variación
                                var posX = 0;
                                heightAcumulado = heightAcumulado + ( itemHeight) + spacingY;

                                var posY = heightAcumulado;

                                heightAcumulado = heightAcumulado + (d.value.lineas-1)*itemHeight;

                                return ("translate(" + posX + "," + posY + ")");
                            });

        //Dibuja los años y los ticks                    
        var ticksAxis = newData.anios.map(function(item){return item.anio})
            ticksAxis.push(primerStartingYear - 3 , ultimoEndingYear + 3);
            ticksAxis.push(1976 , 1983);			
        var xAxis = d3.svg.axis()
                        .scale(xScale)
                        .orient("bottom")
                        .tickFormat(d3.format("0"))
                        .tickValues(ticksAxis);

            svg.append("g").attr("class", "axis")
                .attr("transform", "translate(0," + marginSVG + ")")
                .call(xAxis);
        };
    

    App.getAniosMasUsados = function(data,corte){
            var anios = data.map(function(cargo){return parseInt(cargo['fechainicioyear'])});
                anios.concat(data.map(function(cargo){return parseInt(cargo['fechafinyear'])}));
            var i, count = {};
            for(i=0;i<anios.length;i++){
                if(count[anios[i]]){
                    count[anios[i]]++;
                } else {
                    count[anios[i]] = 1;
                }
            }
            var toOrder = [];
            for(anios in count){
                toOrder.push({ anio : anios, veces : count[anios] });
            }
            return toOrder.sort(function(item1, item2){ return item2.veces - item1.veces }).slice(0,corte);
        }

})(window, document, Tabletop);

window.onload = function() {
    App.init(); 
}