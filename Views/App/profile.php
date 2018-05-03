<!doctype html>
<html lang="en">
<head>

    <?php 
        
        session_start();
        include '../../resources/config.php';
        
        if (isset($_SESSION['login_user']) == false) {
            header("Location: ../UserLogin.php");
        };

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
                <li><a class="active" href="profile.php" id="userName"></a></li>
                <li><a href="logAReading.php">Log a Reading</a></li>
                <li><a href="graphs.php">Graphs</a></li>
                <li><a href="personalData.php">Data</a></li>
                <!-- <li><a href="settings.php">Settings</a></li> -->
                <li><a href="../../resources/logout.php"> Log Out </a></li>
            </ul>
        </nav>

        <div class="content">     
            <div class="title"> Profile </div>
            <img class="help" src="../../Static/images/question-mark-button.png" title="Help">
        </div>
        <div class="page">
               <ul class="profileElements">
                    
                </ul>

        </div>
        
        <script type="text/javascript">
            var FormData = <?php print_r(json_encode(getData('Users', ['_id' => new MongoDB\BSON\ObjectID($_SESSION['login_user']['ID'])])->toArray())); ?>[0];
            
            var profileElements = document.getElementsByClassName('profileElements')[0];
            console.log(FormData)
            FormData['Name'] = FormData['Name']['Text'];
            console.log(profileElements);

            for (element in FormData) {
                if (element != '_id' && element != 'SpecifiedGraphs') {
                    var newElement = document.createElement('li');
                    profileElements.appendChild(newElement);

                    var title = document.createElement('h4');
                    title.innerHTML = element;

                    var value = document.createElement('p')
                    value.innerHTML = FormData[element];

                    newElement.appendChild(title);
                    newElement.appendChild(value);
                };
             }; 

            console.log(FormData[0]);
        </script>
        	  
        <div class="footer"></div>
    </div>
</body>
</html>