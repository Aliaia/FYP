colorOrder = ['#009933', '#ff9933', '#ff0000', '#ff33cc', '#6600ff', '#cc66ff', '#00ff99', '#99ccff', '#ffff00', '#ff0066'];

function createGraph(name, userData, variables) {

	graphData = dataFormatting(variables, userData);

 	var DateMinMax = [graphData[0].Date, graphData[3].Date];
 	var DataMinMax = getRange(graphData);
	
    var width = 1200;
    var height = 300;
    var margin = {top: 20, right: 720, bottom: 70, left: 50};
    var innerwidth = width - margin.left - margin.right;
    var innerheight = height - margin.top - margin.bottom ;

    var vis = d3.select("#newGraphs")
    		.append("svg")
    		.attr("id", name.replace(/\s/g, ''))
            .attr("width", width)
            .attr("height", height)
    	
    function addText(){	

	   	showData.append("tspan")
	    	.text("Graph Data:")
	    	.attr('y', margin.top)
	    	.attr('x', 0)
	    	.style('fill', 'grey')
	    	.style('font-size', '15px')

	    showData.append("tspan")
	    	.text("Roll over a data point to view.")
	    	.attr('y', (margin.top + 20))
	    	.attr('x', (0))
	    	.attr('text-anchor', 'start')
	    	.style('fill', 'grey')
	    	.style('font-size', '10px')

	}

	var showData = d3.select("#newGraphs").select('#' + name.replace(/\s/g, '')).append("text")
    	.attr("id", "showData")
    	.text("")
    	.attr("transform", "translate(" + (margin.right - 70) + "," + (margin.top)+")")

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
    	
    	if(attribute != 'Date'){

	    	var lineGen = d3.svg.line()
	            .x(function(d) {
	              return xScale(d.Date);
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
			    .attr("cx", function (d) { return xScale(d.Date); })
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
    	var position = margin.top
    	
    	showData.text("");
		showData.append("tspan")
	    	.text("Graph Data:")
	    	.attr('y', margin.top)
	    	.attr('x', 0)
	    	.style('fill', 'black')
	    	.style('font-size', '15px')
    	position = position + 20;

    	for (key in points[0]){
    		showData.append("tspan")
	    		.text(format(key) + ": " + (format(points[0][key])))
	    		.attr('y', (position))
	    		.attr('x', (-10))
	    		.style('text-anchor', 'start')
	    		.style('font-size', '10px')

	    	// console.log(points[0][key])
    		position = position + 20;
    	}

        d3.select(this).attr({
          r: 3 * 2
        });
    }

    function format(entry){
    	if (typeof(entry) == "object") {
    		entry = entry.toDateString();
    	} 
    	return entry
    }

    function handleMouseOut(d, i) {
    	showData.text("");
    	addText();

        d3.select(this).attr({
          r: 3
        });
    }

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


