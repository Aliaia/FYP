<!doctype html>
<html lang="en">
<head>

    <?php 
        
        session_start();
        include '../../resources/config.php';
        
        if (isset($_SESSION['login_user']) == false) {
            header("Location: ../UserLogin.php");
        }


    ?>
    <script type="text/javascript">
    function onLoad(){
       document.getElementById("userName").innerHTML = <?php print("'" . $_SESSION['login_user']['Name'] . "'") ?>;
    }
    </script>

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
                <li><a href="profile.php" id="userName"></a></li>
                <li><a href="logAReading.php">Log a Reading</a></li>
                <li><a href="graphs.php">Graphs</a></li>
                <li><a class="active" href="personalData.php">Data</a></li>
                <!-- <li><a href="settings.php">Settings</a></li> -->
                <li><a href="../../resources/logout.php"> Log Out </a></li>
            </ul>
        </nav>

        <div class="content">     
            <div class="title"> Data </div>
            <img class="help" src="../../Static/images/question-mark-button.png" title="Help">
        </div>
        <div class="page">
            <ul class="conditionData">
                
            </ul>
            <script type="text/javascript">
            
                var FormData = <?php print_r(json_encode(getData('ConditionData', ['Identification' => $_SESSION['login_user']['ID']])->toArray())); ?>;
                var list = document.getElementsByClassName("conditionData")[0];

                for (recording in FormData) {
                    var newElement = document.createElement('li');
                    list.appendChild(newElement);

                    var title = document.createElement('h4');
                    title.innerHTML = new Date(FormData[recording]['DateTime']);
                    newElement.appendChild(title);

                    delete FormData[recording]['_id']
                    delete FormData[recording]['Identification']
                    delete FormData[recording]['DateTime']
                    console.log(FormData[recording]);

                    for ( attribute in FormData[recording]) {
                        var data = document.createElement('p');
                        data.innerHTML = (attribute +': ' + FormData[recording][attribute]['Measure'] + ' ' + FormData[recording][attribute]['Unit']);
                        newElement.appendChild(data);
                        // console.log(FormData[recording][attribute]);
                    }

                    // title.innerHTML = FormData[recording];

                };
            </script>



            </div>
        	  
        <div class="footer"></div>

    </div>

</body>
</html>