//selection of colours for graph lines
colorOrder = ['#009933', '#ff9933', '#ff0000', '#ff33cc', '#6600ff', '#cc66ff', '#00ff99', '#99ccff', '#ffff00', '#ff0066'];

//If a user wants to discard a graph, they're asked if they're sure.
function discardGraph(){
    var confirm = window.confirm("Are you sure you want to discard this graph?");

    if(confirm) {
        window.location.href = '../App/graphs.php';
    }
    return false;

}


//creates a customised d3 graph 
function createGraph(name, userData, variables, addLabel) {

    //get the correct variables from the data, and put in the correct format - d3Config.js
	graphData = dataFormatting(variables, userData);

    //if graph has an ID, set it.
    //id is used to label the div
    if(typeof variables[0] !== 'undefined' && typeof variables[0]["$oid"] !== 'undefined') {
        var graphID = variables[0]["$oid"];
        variables.splice(0, 1);
    }

    //find the latest date of all active elements
    var lastDate = 0;
    for (element in variables) {
        var newDate = getLastValue(graphData, variables[element]);
        if (newDate > lastDate) {
            lastDate = newDate
        }
    };

    //set variables for x and y axis, and size of graph
 	var DateMinMax = [graphData[0].Date, graphData[lastDate].Date];
 	var DataMinMax = getRange(graphData);
	var width = 1000;
    var height = 350;
    var margin = {top: 40, right: 520, bottom: 70, left: 50};
    
    //if rollover points aren't required, the graph can be smaller
    if(addLabel == false){
        width = 650;
        margin.top = 20;
        margin.right = 150;
    }
    
    var innerwidth = width - margin.left - margin.right;
    var innerheight = height - margin.top - margin.bottom ;

    //add backgrounds for graph and rollover points to the div.
    var vis = d3.select("#newGraphs")
    		.append("svg")
    		.attr("id", ('A' + graphID))
            .attr("width", width)
            .attr("height", height)

    if (addLabel == true) {
        var showData = d3.select("#newGraphs").select('#A' + graphID)
            .append("text")
            .attr("class", "showData")
            .text("")
            .attr("transform", "translate(" + (margin.left + 700) + "," + (0)+")")

        addText();
    };

    //adds the rollover data points
    	
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
    //for each attribute, create a new line with circles and a label
    for(attribute in variables){
        attribute = variables[attribute];

    	if(attribute != 'Date' && attribute != '$oid' ){

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
		        .attr('fill', 'none');

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
				.attr("transform", "translate(" + (width - margin.right + 9) + "," + (yScale(graphData[getLastValue(graphData, attribute)][attribute]) + margin.top) +")")
				.attr("dy", ".35em")
				.attr("text-anchor", "start")
				.style("fill", colorOrder[0])
				.text(attribute);

            //once a colour is used from the list, add it to the end of the array again
			var old = colorOrder.splice(0, 1);	
            colorOrder.push(old);	

    	}
    }

    //rollovers for data points

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

            //delete identifications, so not to print them to the screen
            //format the date to print nicely
            userData[i] = format(userData[i]);

            //append each measurement to the text area
        	for (key in userData[i]){
                measure = userData[i][key];
                unit = "";
                
                //if a measurement is recorded 
                if (userData[i][key]["Measure"]) {
                    measure = userData[i][key]["Measure"];
                };
                //if a unit is recorded
                if(userData[i][key]["Unit"]) {
                    unit = userData[i][key]["Unit"];
                };
        		
                showData.append("tspan")
    	    		.text(key + ": " + measure + " " + unit)
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

    function format(data){

        delete data["_id"];
        delete data["Identification"];
        data["DateTime"] = new Date(data["DateTime"]);

        return data;

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

	//append x axis grid
    vis.append("g")
        .attr("class", "grid")
        .attr("transform", "translate(" + margin.left + ", " + (height - margin.bottom) + ")")
        .call(make_x_axis(xScale)
            .tickSize((-height + margin.top + margin.bottom), 0, 0)
            .tickFormat("")
        )

    //append y axis grid
    vis.append("g")
        .attr("class", "grid")
        .attr("transform", "translate(" + (margin.left) + "," + (margin.top) + ")")
        .call(make_y_axis(yScale)
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

    //add the link to edit the graph
    var editLink = vis.append('a')
        // .attr("transform", "translate(" + (margin.right - 130) + "," + (0)+")")
        .attr("href", ('../App/createGraph.php?Graph=' + graphID) )
        .attr("title", "Edit Graph");


    editLink.append('image')
        .attr('xlink:href', '../../Static/images/pencil-edit-button.png')
        .attr('class', 'editButton')
        .attr('x', (margin.left + 600))

    }

}


