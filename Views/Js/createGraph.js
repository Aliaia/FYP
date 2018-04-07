colorOrder = ['#009933', '#ff9933', '#ff0000', '#ff33cc', '#6600ff', '#cc66ff', '#00ff99', '#99ccff', '#ffff00', '#ff0066'];

function createGraph(name, userData, variables) {

	graphData = dataFormatting(variables, userData);

 	var DateMinMax = [graphData[0].x, graphData[3].x];
 	var DataMinMax = getRange(graphData);
	
    var width = 1000;
    var height = 300;
    var margin = {top: 20, right: 520, bottom: 70, left: 50};
    var innerwidth = width - margin.left - margin.right;
    var innerheight = height - margin.top - margin.bottom ;

    var vis = d3.select("#newGraphs")
    		.append("svg")
    		.attr("id", name)
            .attr("width", width)
            .attr("height", height)
    	
    function addText(){	

	   	showData.append("tspan")
	    	.text("Graph Data:")
	    	.attr('y', margin.top)
	    	.style('fill', 'grey')

	    showData.append("tspan")
	    	.text("Roll over a data point to view.")
	    	.attr('y', (margin.top + 20))
	    	.style('fill', 'grey')

	}

	var showData = d3.select("#newGraphs").select("svg").append("text")
    	.attr("class", "showData")
    	.text("")
    	.attr("transform", "translate(" + (margin.right + 120) + "," + (margin.top)+")")
    
    addText();

    var xScale = d3.time.scale()
        .range([0, innerwidth])
        .domain(DateMinMax);

    var yScale = d3.scale.linear()
        .range([innerheight, 0])
        .domain(DataMinMax);

    var xAxis = d3.svg.axis().scale(xScale).ticks(10);
      
    var yAxis = d3.svg.axis().scale(yScale).orient("left");

    for(attribute in graphData[0]){
    	
    	if(attribute != 'x'){

	    	var lineGen = d3.svg.line()
	            .x(function(d) {
	              return xScale(d.x);
	            })
	            .y(function(d) {
	              return yScale(d[attribute]);
	            })
	           .interpolate("linear");

	        vis.append('svg:path')
		        .attr('d', lineGen(graphData))
		        .attr("transform", "translate(" + (margin.left) + "," + (margin.top)+")")
		        .attr('stroke', colorOrder[0])
		        .attr('stroke-linecap', 'round')
		        .attr('stroke-width', 1)
		        .attr('fill', 'none')
		        .attr("data-legend", attribute);

			var circles = vis.selectAll("point")
			    .data(graphData)
			    .enter()

		    circles.append("circle")
			    .attr("cx", function (d) { return xScale(d.x); })
			    .attr("cy", function (d) {return yScale(d[attribute]); })
			    .attr("transform", "translate(" + (margin.left) + "," + (margin.top) + ")")
			    .attr('stroke', colorOrder[0])
			    .attr("fill", colorOrder[0])
			    .attr("r", 3)
			    .on("mouseover", handleMouseOver)
           		.on("mouseout", handleMouseOut);



			console.log(colorOrder[0]);

			vis.append("text")
				.attr("class", "legendText")
				.attr("transform", "translate(" + (width - margin.right + 9) + "," + yScale(graphData[graphData.length -1][attribute] - 9) + ")")
				.attr("dy", ".35em")
				.attr("text-anchor", "start")
				.style("fill", colorOrder[0])
				.text(attribute);

			colorOrder.splice(0, 1);		

    	}
    }

    function handleMouseOver(d, i) {
    	var points = d3.select(this).data();
    	showData.text("");
    	showData.append("tspan")
    		.text("Selected Point: ")
    		.attr('y', margin.top)

    	// showData.text(points[0]['Diastolic Pressure']);
        d3.select(this).attr({
          r: 3 * 2
        });
    }

    function handleMouseOut(d, i) {
    	showData.text("");
    	addText();

        d3.select(this).attr({
          r: 3
        });
    }

    //data line variables
    var lineGen = d3.svg.line()
          .x(function(d) {
            return xScale(d.x);
          })
          .y(function(d) {
            return yScale(d['Diastolic Pressure']);
          })
          .interpolate("linear");

    //create x axis
    function make_x_axis() {
        return d3.svg.axis()
            .scale(xScale)
            .orient("bottom")
            .ticks(5)
    }

    //create y axis
    function make_y_axis() {
        return d3.svg.axis()
            .scale(yScale)
            .orient("left")
            .ticks(5)
    }

	//append x axis grid
    vis.append("g")
        .attr("class", "grid")
        .attr("transform", "translate(" + margin.left + ", " + (height - margin.bottom) + ")")
        .call(make_x_axis()
            .tickSize((-height + margin.top + margin.bottom), 0, 0)
            .tickFormat("")
        )

    //append y axis grid
    vis.append("g")
        .attr("class", "grid")
        .attr("transform", "translate(" + (margin.left) + "," + (margin.top) + ")")
        .call(make_y_axis()
            .tickSize((-width + margin.left + margin.right), 0, 0)
            .tickFormat("")
        )

    //append x axis
    vis.append("svg:g")
        .attr("class", "xaxis")
        .attr("transform", "translate(" + (margin.left) + "," + (height - margin.bottom) + ")")
        .call(xAxis)
        .selectAll("text") 
            .attr("dx", "-.0em")
            .attr("dy", ".80em");

    //append y axis
    vis.append("svg:g")
        .attr("transform", "translate(" + (margin.left) + "," + (margin.top)+")")
        .attr('stroke-width', 1)
        .call(yAxis)
        .selectAll("text")
        	.attr("dx", "-.80em")
            .attr("dy", ".30em");

    //append x axis title
    vis.append("text")
        .attr("transform", "translate(" + ((width - margin.right)/2) + " ," + (height - margin.top - 15) + ")")
        .text("Measurement Date");

    //append title
    vis.append("text")
        .attr("x", ((width - margin.right) / 2))             
        .attr("y", 0 + (margin.top / 2))
        .attr("text-anchor", "middle")   
        .text(name + " over time");

}


