<!doctype html>
<html lang="en">
<head>

    <?php 
        
        session_start();
        include '../../resources/config.php';
        
        if (isset($_SESSION['login_user']) == false) {
            header("Location: ../Views/UserLogin.php");
        }

        $activeElements = '';
        echo ("<script> var GraphType = '';</script>");

        if (isset($_GET['Graph'])) {
            $UserData = getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray();
            $UserData = json_decode(json_encode($UserData[0]),true);

            $Graphs = $UserData['SpecifiedGraphs'];
            foreach ($Graphs as $GraphName => $GraphElements) {
                if ($GraphElements[0]['$oid'] == $_GET['Graph']) {
                    echo ("<script> var GraphType = '" . $GraphName . "';</script>");
                    $activeElements = $GraphElements;
                    break;
                }
            }
        }

        

    ?>
    <script type="text/javascript">
    function onLoad(){
       document.getElementById("userName").innerHTML = <?php print("'" . $_SESSION['login_user']['Name'] . "'") ?>;
    }

    function changeGraphElements(attribute){
        var element = document.getElementById(attribute);
        var graphElement = document.querySelector('svg');
        
        if (activeElements.includes(attribute)){
            var index = activeElements.indexOf(attribute);
            activeElements.splice(index, 1);
            element.setAttribute('class', 'addAttribute');
        } else {
            element.setAttribute('class', 'addAttribute active');
            activeElements.push(attribute);
        }
        graphElement.remove();
        createGraph('Create New Graph', userData, activeElements, false);

        var hidden = document.getElementById('graphElements');
        hidden.setAttribute('value', activeElements);
    };

    function formValidation(){
        var graphTitle = document.forms['createForm']['graphName'].value;
        var activeElements = document.forms['createForm']['activeElements'].value;
        if (graphTitle == "" || activeElements == "") {
            var errorElement = document.getElementById('error');
            errorElement.innerHTML = "Please enter a Name or elements";
            return false;
        } else {
            return true;
        };
    }


    </script>
    <script src="http://d3js.org/d3.v2.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/d3-legend/1.1.0/d3-legend.js"></script>
    <script type="text/javascript" src="../Js/d3Config.js"></script>
    <script type="text/javascript" src="../Js/createGraph.js"></script>
    <script type="text/javascript" src="../Js/createField.js"></script>

    <meta charset="UTF-8">

    <title>Condition Tracker</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/appStyle.css"> <!-- custom styles -->

</head>
<body onload="onLoad()">
    <div class="container">

    	<h1>Condition Tracker</h1>
          <h2>An easier way to track health conditions</h2>

    </div>

    <div class="container2">

        <nav class="navigation">
        	  	
          	<ul class="Nav-bar">
                <li><a class="active" href="profile.php" id="userName"></a></li>
                <li><a href="logAReading.php">Log a Reading</a></li>
                <li><a href="graphs.php">Graphs</a></li>
                <!-- <li><a href="personalData.php">Data</a></li> -->
                <!-- <li><a href="settings.php">Settings</a></li> -->
                <li><a href="../../resources/logout.php"> Log Out </a></li>
            </ul>
        </nav>

        <div class="content">     
            <div class="title"> Create Graph </div>
            <img class="help" src="../../Static/images/question-mark-button.png" title="Help">

            <div class="graphPage">
                <p id="error"></p> 
                <form name="createForm" method="post" action="../../resources/newGraph.php" onsubmit="return formValidation()">
                    <label class="graphNameLabel"><b>Graph Name: </b></label>
                    <input type="text" name="graphName" class="graphTitle">
                    <input type="hidden" id="graphID" name="graphID" value="">
                    <input type="hidden" name="activeElements" id="graphElements" value="" >
                    <ul id="attributes">
                        <li><p><b>Graph Elements</b></p></li>
                        
                    </ul>
                </form>

                <div id="newGraphs">
                    <script type="text/javascript">

                        var FormData = <?php print_r(json_encode((getConditionData($_SESSION['login_user']['ID']))->toArray())); ?>;

                        var attributes = getUniqueAttributes(FormData);

                        var list = document.getElementById("attributes");

                        if (GraphType == ''){
                            // console.log(attributes[0][0]);
                            var activeElements = [attributes[0][0]];

                        } else {

                            var activeElements = <?php print_r(json_encode($activeElements)) ?>;
                            document.getElementsByClassName('graphTitle')[0].value = GraphType;

                            var hiddenElement = document.getElementById("graphID");
                            hiddenElement.setAttribute('value', activeElements[0]['$oid']);
                            activeElements.splice(0, 1);
                        
                        };

                        var hidden = document.getElementById('graphElements');
                        hidden.setAttribute('value', activeElements);

                        for (attribute in attributes) {
                            var attributeElement = document.createElement('li');
                            attributeElement.setAttribute('class', 'addAttribute');
                            attributeElement.setAttribute('id', attributes[attribute][0]);
                            list.appendChild(attributeElement);
                            
                            var text = document.createElement('a')
                            text.textContent = attributes[attribute][0];
                            text.setAttribute('onclick', ('changeGraphElements("' + attributes[attribute][0] + '") '));

                            if (activeElements.includes(attributes[attribute][0])){
                                attributeElement.setAttribute('class', 'addAttribute active')
                            };

                            attributeElement.appendChild(text);

                        }

                        var submitButton = document.createElement('li');
                        submitButton.setAttribute('class', 'addAttribute');
                        list.appendChild(submitButton);

                        var button = document.createElement('input');
                        button.setAttribute('type', 'submit');
                        button.setAttribute('class', 'createButton');
                        button.setAttribute('value', 'Create Graph');
                        submitButton.appendChild(button);

                        var userData = <?php print_r(json_encode(getConditionData($_SESSION['login_user']['ID'])->toArray())); ?> 

                        createGraph('Create New Graph', userData, activeElements, false);
                    
                    </script>


                </div>
            </div>
        </div> 
        	  
        <div class="footer"></div>
    </div>
</body>
</html>