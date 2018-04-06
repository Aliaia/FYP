<!doctype html>
<html lang="en">
<head>

    <?php 
        
        session_start();
        
        if (isset($_SESSION['login_user']) == false) {
            header("Location: ../Views/UserLogin.php");
        }

        include '../../resources/config.php';

    ?>
    <script type="text/javascript" src="../Js/d3Config.js"></script>
    <script type="text/javascript">
    
    function onLoad(){
        var identification = <?php print_r("'" . $_SESSION['login_user']['ID'] . "'") ?>;
        document.getElementById("userName").innerHTML = <?php print("'" . $_SESSION['login_user']['Name'] . "'") ?>;
    }

    
    </script>

    <meta charset="UTF-8">

    <title>Condition Tracker</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/appStyle.css"> 
    <!-- D3 -->
    <script src="http://d3js.org/d3.v2.js"></script>

</head>
<body onload="onLoad()">
    <div class="container">

        <h1>Condition Tracker</h1>
          <h2>An easier way to track health conditions</h2>

    </div>

    <nav class="navigation">
            
        <ul class="Nav-bar">
            <li><a href="profile.php" id="userName"></a></li>
            <li><a href="logAReading.php">Log a Reading</a></li>
            <li><a class="active" href="graphs.php">Graphs</a></li>
            <li><a href="personalData.php">Data</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="../UserLogin.php"> Log Out </a></li>
        </ul>
    </nav>

    <div class="content">     
        <div class="title"> User Graphs </div>
        <img class="help" src="../../Static/images/question-mark-button.png" title="Help">

        <div class="systolicAndDiastolic">
            <svg id="systolicAndDiastolic" width = "500" height = "300">
                <script type="text/javascript">

                    var userData = <?php print_r(json_encode(getConditionData('5abcf1fb331aaf3b548aee46')->toArray())); ?> 

                    var graphData = dataFormatting('Diastolic', userData);
                    minDate = graphData[0].x;
                    maxDate = graphData[2].x;

                    var vis = d3.select("#systolicAndDiastolic");
                    var width = 500;
                    var height = 300;
                    var margin = {top: 20, right: 20, bottom: 70, left: 50};
                    var innerwidth = width - margin.left - margin.right;
                    var innerheight = height - margin.top - margin.bottom ;

                    var xScale = d3.time.scale()
                                .range([0, innerwidth])
                                .domain([minDate,maxDate]);

                    var yScale = d3.scale.linear()
                                .range([innerheight, 0])
                                .domain([0, 100]);

                    var xAxis = d3.svg.axis().scale(xScale).ticks(10);//.tickFormat(d3.time.format("%m-%d"));
                      
                    var yAxis = d3.svg.axis().scale(yScale).orient("left");

                    var lineGen = d3.svg.line()
                          .x(function(d) {
                            return xScale(d.x);
                          })
                          .y(function(d) {
                            return yScale(d.y);
                          })
                          .interpolate("linear");

                    function make_x_axis() {        
                        return d3.svg.axis()
                            .scale(xScale)
                             .orient("bottom")
                             .ticks(5)
                    }

                    function make_y_axis() {        
                        return d3.svg.axis()
                            .scale(yScale)
                            .orient("left")
                            .ticks(5)
                    }

                    vis.append("g")         
                        .attr("class", "grid")
                        .attr("transform", "translate(0," + (height - margin.bottom) + ")")
                        .call(make_x_axis()
                            .tickSize((-height + margin.top + margin.bottom), 0, 0)
                            .tickFormat("")
                        )
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
                            .style("font", "10px sans-serif")  
                            .style("text-anchor", "end")
                            .attr("dx", "-.8em")
                            .attr("dy", ".15em");

                    //append y axis
                    vis.append("svg:g")
                        .attr("transform", "translate(" + (margin.left) + "," + (margin.top)+")")
                        .attr('stroke-width', 1)
                        .call(yAxis)
                        .selectAll("text")
                            .style("font", "10px sans-serif");

                    //append data
                    vis.append('svg:path')
                        .attr('d', lineGen(graphData))
                        .attr("transform", "translate(" + (margin.left) + "," + (margin.bottom)+")")
                        .attr('stroke', 'green')
                        .attr('stroke-width', 1)
                        .attr('fill', 'none');

                    vis.append("text")             
                        .attr("transform", "translate(" + (width/2) + " ," + (height - margin.top - 20) + ")")
                        .style("text-anchor", "middle")
                        .style("font", "12px sans-serif")
                        .text("Measurement Date");

                    vis.append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 0)
                        .attr("x",0 - (height / 2))
                        .attr("dy", "1em")
                        .style("text-anchor", "middle")
                        .style("font", "12px sans-serif")
                        .text("Pressure (Mmhg)");

                    vis.append("text")
                        .attr("x", (width / 2))             
                        .attr("y", 0 + (margin.top / 2))
                        .attr("text-anchor", "middle")  
                        .style("font", "12px sans-serif") 
                        .style("text-decoration", "underline")  
                        .text("Diastolic Pressure over time");

                </script>
            </<svg>
        </div>
    </div>

          
    <div class="footer"></div>
</body>
</html>
