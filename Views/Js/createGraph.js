colorOrder = ['#009933', '#ff9933', '#ff0000', '#ff33cc', '#6600ff', '#cc66ff', '#00ff99', '#99ccff', '#ffff00', '#ff0066'];

function getLastValue(data, attribute){
    for (var i = data.length - 1; i >= 0; i--) {
        if(data[i][attribute]){
            return i
        }
    };
}

function createGraph(name, userData, variables, addLabel) {

	graphData = dataFormatting(variables, userData);

    var lastDate = 0;
    for (element in variables) {
        var newDate = getLastValue(graphData, variables[element]);
        if (newDate > lastDate) {
            lastDate = newDate
        }
    };

 	var DateMinMax = [graphData[0].Date, graphData[lastDate].Date];
 	var DataMinMax = getRange(graphData);
	var width = 1200;
    var height = 350;
    var margin = {top: 40, right: 720, bottom: 70, left: 50};
    
    if(addLabel == false){
        width = 650;
        margin.top = 20;
        margin.right = 150;
    }
    
    var innerwidth = width - margin.left - margin.right;
    var innerheight = height - margin.top - margin.bottom ;

    var vis = d3.select("#newGraphs")
    		.append("svg")
    		.attr("id", name.replace(/[^\w]|_/g, ''))
            .attr("width", width)
            .attr("height", height)

    if (addLabel == true) {
        var element = document.getElementById(name.replace(/[^\w]|_/g, ''));
        console.log(element);
        var showData = d3.select("#newGraphs").select('#' + name.replace(/[^\w]|_/g, '')).append("text")
            .attr("class", "showData")
            .text("")
            .attr("transform", "translate(" + (margin.right - 60) + "," + (0)+")")

        addText();
    };
    	
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

    var xScale = d3.time.scale()
        .range([0, innerwidth])
        .domain(DateMinMax);

    var yScale = d3.scale.linear()
        .range([innerheight, 0])
        .domain(DataMinMax);

    var xAxis = d3.svg.axis().scale(xScale).ticks(10);
      
    var yAxis = d3.svg.axis().scale(yScale).orient("left");
    for(attribute in graphData[0]){

    	if(attribute != 'Date' ){

	    	var lineGen = d3.svg.line()
	            .y(function(d) { return yScale(d[attribute]); })
                .defined(function(d) { 
                    if(d[attribute]){
                        return true;
                    } else {
                        return false;
                    } }) 
                .x(function(d) { return xScale(d.Date); })
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
                .attr("cy", function (d) {return yScale( d[attribute]); })
			    .attr("cx", function (d) { return xScale( d.Date ); })
			    .attr("transform", "translate(" + (margin.left) + "," + (margin.top) + ")")
			    .attr('stroke', colorOrder[0])
			    .attr("fill", colorOrder[0])
			    .attr("r", function(d) { return d[attribute] == null ? 0 : 3; })
			    .on("mouseover", handleMouseOver)
           		.on("mouseout", handleMouseOut);

			vis.append("text")
				.attr("class", "legendText")
				.attr("transform", "translate(" + (width - margin.right + 9) + "," + yScale(graphData[getLastValue(graphData, attribute)][attribute] - 9) + ")")
				.attr("dy", ".35em")
				.attr("text-anchor", "start")
				.style("fill", colorOrder[0])
				.text(attribute);

			var old = colorOrder.splice(0, 1);	
            colorOrder.push(old);	

    	}
    }

    function handleMouseOver(d, i) {
        if (addLabel == true){
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

        		position = position + 20;
        	}

            d3.select(this).attr({
              r: 3 * 2
            });
        };
    }

    function format(entry){
    	if (typeof(entry) == "object") {
    		entry = entry.toDateString();
    	} 
    	return entry
    }

    function handleMouseOut(d, i) {
        if (addLabel == true) {
        	showData.text("");
        	addText();

            d3.select(this).attr({
              r: 3
            });
        }
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
        .attr("transform", "translate(" + ((width - margin.right)/2) + " ," + (height - margin.top + 10) + ")")
        .text("Measurement Date");

    //append title
    if (addLabel) {
        vis.append("text")
            .attr("x", ((width - margin.right) / 2))             
            .attr("y", 0 + (margin.top / 2))
            .attr("text-anchor", "middle")   
            .text(name);
    }

}


