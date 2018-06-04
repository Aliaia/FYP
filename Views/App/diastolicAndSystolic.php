<!doctype html>
<html lang="en">
<head>

    <?php 
        
        session_start();
        
        if (isset($_SESSION['login_user']) == false) {
            header("Location: ../ProfessionalLogin.php");
        }

        //check to see if professional has paatient id in extension else no access to page

        include '../../resources/config.php';

        $userData = getConditionData($_GET['id']) ->toArray();

    ?>

    <script type="text/javascript">

        function onLoad(){

        }

    </script>

    <link rel="stylesheet" href="../css/appStyle.css"> <!-- custom styles -->
    <script type="text/javascript" src="../Js/d3Config.js"></script>
    <script src="http://d3js.org/d3.v2.js"></script>

</head>
<body onload="onLoad()">
    <div class="container">

    	<h1>Condition Tracker</h1>
          <h2>An easier way to track health conditions</h2>

    </div>

    <div class="container2">

        <nav class="navigation">
        	  	
          	<ul class="Nav-bar">
                <li><a href="patients.php">Patients List</a></li>
                <li><a href="../../resources/logout.php"> Log Out </a></li>
                <li><a href= <?php echo("'patientProfile.php?id=" . $_GET['id'] . "'") ?> > Profile </li>
                <li><a href=<?php echo("'diastolicAndSystolic.php?id=" . $_GET['id'] . "'") ?> class="active" > Disatolic and Systolic Pressure </a></li>
                <li><a href=<?php echo("'diastolicSystolicAndMedication.php?id=" . $_GET['id'] . "'") ?>> Diastolic, Systolic and medication changes </a></li>
                <li><a href= <?php echo("'pulse.php?id=" . $_GET['id'] . "'") ?>> Pulse </a></li>
            </ul>
        </nav>
        
        <div class="content">     
            <div class="title"> Diastolic and Systolic Pressure </div>
            <img class="help" src="../../Static/images/question-mark-button.png" title="Help">
        </div>
        <div class="page">

            <div id="graph"></div>
            <div id="table"></div>

        <!-- Create Graph -->
        <script type="text/javascript">
            var variables = ['Diastolic_Pressure', 'Systolic_Pressure', 'Identification'];
            var rawUserData = <?php print_r(json_encode($userData)) ?>;
            var userData = dataFormatting(variables, rawUserData);
            var variables = ['Diastolic_Pressure', 'Systolic_Pressure'];
            console.log(rawUserData);

            var lastDate = 0;
            for (element in variables) {
                var newDate = getLastValue(userData, variables[element]);
                if (newDate > lastDate) {
                    lastDate = newDate
                }
            };

            var DateMinMax = [userData[0].Date, userData[lastDate].Date];
            var DataMinMax = getRange(userData);
            var width = 1000;
            var height = 450;
            var margin = {top: 40, right: 220, bottom: 70, left: 50};
            var innerwidth = width - margin.left - margin.right;
            var innerheight = height - margin.top - margin.bottom ;

            var vis = d3.select("#graph")
                .append("svg")
                .attr("width", width)
                .attr("height", height)

            var xScale = d3.time.scale()
                .range([0, innerwidth])
                .domain(DateMinMax);

            var yScale = d3.scale.linear()
                .range([innerheight, 0])
                .domain(DataMinMax);

            var xAxis = d3.svg.axis().scale(xScale).ticks(10);
      
            var yAxis = d3.svg.axis().scale(yScale).orient("left");

            console.log(vis);

            vis.append("g")
                .attr("class", "grid")
                .attr("transform", "translate(" + margin.left + ", " + (height - margin.bottom) + ")")
                .call(make_x_axis(xScale)
                    .tickSize((-height + margin.top + margin.bottom), 0, 0)
                    .tickFormat("")
                );
                
            console.log('true');


            vis.append("g")
                .attr("class", "grid")
                .attr("transform", "translate(" + (margin.left) + "," + (margin.top) + ")")
                .call(make_y_axis(yScale)
                    .tickSize((-width + margin.left + margin.right), 0, 0)
                    .tickFormat("")
                )


            vis.append("svg:g")
                .attr("class", "xaxis")
                .attr("transform", "translate(" + (margin.left) + "," + (height - margin.bottom) + ")")
                .call(xAxis)
                .selectAll("text") 
                .attr("dx", "-.0em")
                .attr("dy", ".80em");


            vis.append("svg:g")
                .attr("transform", "translate(" + (margin.left) + "," + (margin.top)+")")
                .attr('stroke-width', 1)
                .call(yAxis)
                .selectAll("text")
                .attr("dx", "-.80em")
                .attr("dy", ".30em");

            vis.append("text")
                .attr("transform", "translate(" + ((width - margin.right)/2) + " ," + (height - margin.top + 10) + ")")
                .text("Measurement Date");

            vis.append("text")
                .attr("x", ((width - margin.right) / 2))             
                .attr("y", 0 + (margin.top / 2))
                .attr("text-anchor", "middle")   
                .text("Diastolic Pressure and Systolic Pressure over Time");

            for(attribute in variables){

                attribute = variables[attribute];

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
                .attr('d', lineGen(userData))
                .attr("transform", "translate(" + (margin.left) + "," + (margin.top)+")")
                .attr('stroke', "red")
                .attr('stroke-linecap', 'round')
                .attr('stroke-width', 1)
                .attr('fill', 'none');

                var circles = vis.selectAll("point")
                .data(userData)
                .enter()

                circles.append("circle")
                .attr("cy", function (d) {return yScale( d[attribute]); })
                .attr("cx", function (d) { return xScale( d.Date ); })
                .attr("transform", "translate(" + (margin.left) + "," + (margin.top) + ")")
                .attr('stroke', "red")
                .attr("fill", "red")
                .attr("r", function(d) { return d[attribute] == null ? 0 : 3; })
                .on("mouseover", handleMouseOver)
                .on("mouseout", handleMouseOut);

                vis.append("text")
                .attr("class", "legendText")
                .attr("transform", "translate(" + (width - margin.right + 9) + "," + (yScale(userData[getLastValue(userData, attribute)][attribute]) + margin.top) +")")
                .attr("dy", ".35em")
                .attr("text-anchor", "start")
                .style("fill", "red")
                .text(attribute);
            }

            function handleMouseOver(data, index) {

                var date = new Date(d3.select(this).data()[0]['Date']);
                var sPressure;
                var dPressure;
                console.log(d3.select(this).data()[0])
                
                if(d3.select(this).data()[0].hasOwnProperty('Diastolic_Pressure') ) {
                    dPressure = d3.select(this).data()[0]['Diastolic_Pressure'];
                }
                if(d3.select(this).data()[0].hasOwnProperty('Systolic_Pressure')) {
                    sPressure = d3.select(this).data()[0]['Systolic_Pressure'];
                }
                var tableRows = d3.selectAll("tr").filter( function(d){
                    if (d == null) {
                        return false
                    } else if (d.hasOwnProperty('Diastolic_Pressure') && d.hasOwnProperty('Systolic_Pressure')) {
                        return ((new Date(d['DateTime']).getTime() == date.getTime()) && (d['Diastolic_Pressure']['Measure'] == dPressure) && (d['Systolic_Pressure']['Measure'] == sPressure));
                    } else if (d.hasOwnProperty('Diastolic_Pressure')) {
                        return ((new Date(d['DateTime']).getTime() == date.getTime()) && (d['Diastolic_Pressure']['Measure'] == dPressure));
                    } else if (d.hasOwnProperty('Systolic_Pressure')) {
                        return ((new Date(d['DateTime']).getTime() == date.getTime()) && (d['Systolic_Pressure']['Measure'] == sPressure));
                    }
                })

                tableRows.style({background: '#c5cdd3'});
                d3.select(this).attr({
                  r: 6
                });
            }

            function handleMouseOut(data, inddex) {

                var date = new Date(d3.select(this).data()[0]['Date']);
                var sPressure;
                var dPressure;
                
                if(d3.select(this).data()[0].hasOwnProperty('Diastolic_Pressure') ) {
                    dPressure = d3.select(this).data()[0]['Diastolic_Pressure'];
                }
                if(d3.select(this).data()[0].hasOwnProperty('Systolic_Pressure')) {
                    sPressure = d3.select(this).data()[0]['Systolic_Pressure'];
                }
                var tableRows = d3.selectAll("tr").filter( function(d){
                    if (d == null) {
                        return false
                    } else if (d.hasOwnProperty('Diastolic_Pressure') && d.hasOwnProperty('Systolic_Pressure')) {
                        return ((new Date(d['DateTime']).getTime() == date.getTime()) && (d['Diastolic_Pressure']['Measure'] == dPressure) && (d['Systolic_Pressure']['Measure'] == sPressure));
                    } else if (d.hasOwnProperty('Diastolic_Pressure')) {
                        return ((new Date(d['DateTime']).getTime() == date.getTime()) && (d['Diastolic_Pressure']['Measure'] == dPressure));
                    } else if (d.hasOwnProperty('Systolic_Pressure')) {
                        return ((new Date(d['DateTime']).getTime() == date.getTime()) && (d['Systolic_Pressure']['Measure'] == sPressure));
                    }
                })

                tableRows.style({background: '#e2e6e9'});
                d3.select(this).attr({
                  r: 3
                });
            }

        </script>

        <!-- Create table -->
        <script type="text/javascript">
            var cleanUserData = [];
            var titles = d3.keys(rawUserData[0]);
            var unused = ['_id', 'Identification']
            for (var i = titles.length - 1; i >= 0; i--) {
                if (unused.includes(titles[i])) {
                    titles.splice(i, 1)
                }
            };

            var width = 900;
            var height = 400;
            var table = document.getElementById('dataTable');
            var vis = d3.select("#table")
            var table = vis.append('table');
            var headers = table.append('thead').append('tr')
                .selectAll('th')
                .data(titles).enter()
                .append('th')
                .text(function (d) {
                    return d;
                })
            var rows = table.append('tbody').selectAll('tr')
                .data(rawUserData).enter()
                .append('tr')
                .on("mouseover", tableMouseOver)
                .on("mouseout", tableMouseOut);

            function tableMouseOver(data, index) {
                var date = new Date(d3.select(this).data()[0]['DateTime']);
                var sPressure;
                var dPressure;

                if(d3.select(this).data()[0].hasOwnProperty('Diastolic_Pressure') ) {
                    dPressure = d3.select(this).data()[0]['Diastolic_Pressure']['Measure'];
                }
                if(d3.select(this).data()[0].hasOwnProperty('Systolic_Pressure')) {
                    sPressure = d3.select(this).data()[0]['Systolic_Pressure']['Measure'];
                }

                var circles = d3.selectAll("circle").filter( function(d){
                    return ((d['Date'].getTime() == date.getTime()) && (d['Diastolic_Pressure'] == dPressure) && (d['Systolic_Pressure'] == sPressure));
                    
                });
                circles.attr( 'r', function(d){ 
                    return (d3.select(this).attr('r') * 2);
                });

            }

            function tableMouseOut(d, i){
                var date = new Date(d3.select(this).data()[0]['DateTime']);
                var sPressure;
                var dPressure;

                if(d3.select(this).data()[0].hasOwnProperty('Diastolic_Pressure') ) {
                    dPressure = d3.select(this).data()[0]['Diastolic_Pressure']['Measure'];
                }
                if(d3.select(this).data()[0].hasOwnProperty('Systolic_Pressure')) {
                    sPressure = d3.select(this).data()[0]['Systolic_Pressure']['Measure'];
                }

                var circles = d3.selectAll("circle").filter( function(d){
                    return ((d['Date'].getTime() == date.getTime()) && (d['Diastolic_Pressure'] == dPressure) && (d['Systolic_Pressure'] == sPressure));
                
                });
                circles.attr( 'r', function(d){ 
                    return (d3.select(this).attr('r') / 2);
                });

            }

            rows.selectAll('td')
                .data(function (d) {
                    return titles.map(function (k) {
                        if(d[k] && !d[k]['Measure']) {
                            var date = new Date(d[k]);
                            date = date.getDate() + '-' + (date.getMonth()+1) + '-' +date.getFullYear();
                            return { 'value': date, 'name': k};
                        };

                        if (d[k]) {
                            return { 'value': (Object.values(d[k])).join(''), 'name': k};
                        };

                        return { 'value': d[k], 'name': k};
                    });
                }).enter()
                .append('td')
                .text(function (d) {
                    return d.value;
                });

        </script>


        </div>

        <div class="footer"></div>
    </div>
</body>
</html>