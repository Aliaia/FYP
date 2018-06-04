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

        $userData = getdata('Users', ['_id' => new MongoDB\BSON\ObjectID($_GET['id'])]) -> toArray()[0];
    ?>

    <script type="text/javascript">

        function onLoad(){

            <?php  ?>

            document.getElementsByClassName("title")[0].innerHTML = <?php print("'" . json_decode(json_encode($userData), true)['Name']['Text'] . "'") ?>;
        }

    </script>

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
                <li><a href="patients.php">Patients List</a></li>
                <li><a href="../../resources/logout.php"> Log Out </a></li>
                <li><a href= <?php echo("patientProfile.php?id=" . $_GET['id'] . "'") ?> class="active"> Profile </li>
                <li><a href=<?php echo("'diastolicAndSystolic.php?id=" . $_GET['id'] . "'") ?>> Disatolic and Systolic Pressure </a></li>
                <li><a href=<?php echo("'diastolicSystolicAndMedication.php?id=" . $_GET['id'] . "'") ?>> Diastolic, Systolic and medication changes </a></li>
                <li><a href= <?php echo("'pulse.php?id=" . $_GET['id'] . "'") ?>> Pulse </a></li>
            </ul>
        </nav>
        
        <div class="content">     
            <div class="title">  </div>
            <img class="help" src="../../Static/images/question-mark-button.png" title="Help">
        </div>
        <div class="page">
            <ul class="profileElements">
                    
            </ul>

        <script type="text/javascript">

            var profileElements = document.getElementsByClassName('profileElements')[0];

            var userData = <?php print_r(json_encode($userData)) ?>;

            for (element in userData) {
                console.log(FormData[element]);

                    if (element == 'Extension2') {

                        var newElement = document.createElement('li');
                        profileElements.appendChild(newElement);

                        var title = document.createElement('h4');
                        title.innerHTML = 'Patient Medication Changes';

                        newElement.appendChild(title);

                        for (medication in userData[element]['ValueCodeableConcept'] ) {
                            var value = document.createElement('p');
                            if (!userData[element]['ValueCodeableConcept'][medication][2]) {
                                userData[element]['ValueCodeableConcept'][medication][2] = "Present";
                            }
                            value.innerHTML = ("Drug Name:   " + medication + "</br>" + "Dosage:    " + userData[element]['ValueCodeableConcept'][medication][1] + "</br>" + "Start Date: " + userData[element]['ValueCodeableConcept'][medication][2] + "</br>" +  "End Date: " + userData[element]['ValueCodeableConcept'][medication][3]);
                            newElement.appendChild(value);
                        }


                    } else if (element != '_id' && element != 'Name' && element !='Extension0' && element != 'Extension1') {

                        var newElement = document.createElement('li');
                        profileElements.appendChild(newElement);

                        var title = document.createElement('h4');
                        title.innerHTML = element;

                        var value = document.createElement('p')
                        value.innerHTML = userData[element];

                        newElement.appendChild(title);
                        newElement.appendChild(value);
                    
                    } ;

            }; 

        </script>
        </div>

        <div class="footer"></div>
    </div>
</body>
</html>