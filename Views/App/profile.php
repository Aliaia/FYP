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

    function editMedication(medication) {
        console.log(medication.id);
        console.log(FormData['Extension2']['ValueCodeableConcept'][medication.id]);
        drugData = FormData['Extension2']['ValueCodeableConcept'][medication.id];

        console.log(drugData[1]);

        medication.innerHTML = (
            " <form name='changeMedication' action='../../resources/changeMedication.php' method='post' >"
             + "<input type='hidden' name='id' value='" + drugData[0]['$oid'] + "'/>" 
             + "Drug Name:   " + "<br>" +  "<input type='text' name='drugName' value=" +medication.id + " >" + "</br>" 
             + "</br>" + "Dosage:    " + "<br>" + "<input type='text' name='dosage'  value = "+ (drugData[1]) +"> " + "</br>"
             + "</br>" + "Start Date: " + "<br> " + "<input type='text' name='startDate' value="+ drugData[2]+"> " + "</br>"
             + "</br>" +  "End Date: " + "<br>" +  " <input type='text' name='endDate' value=" + drugData[3] +"> " 
             + "</br>" + "<input type='submit' value='Save Changes'/> </form>"
        );

    }

    function addMedication(){
        var medications = document.getElementsByClassName('medicationTitle')[0];

        var editButtons = document.getElementsByClassName('editMedication');

        console.log(editButtons);
        var form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', '../../resources/newMedication.php');
        form.setAttribute('class', 'newMedicationForm')
        medications.appendChild(form);

        var labels = document.createElement('div');
        form.appendChild(labels);

        var inputs = document.createElement('div')
        inputs.setAttribute('class', 'inputsBox');
        form.appendChild(inputs);

        var namelabel = document.createElement('p');
        namelabel.innerHTML = 'Drug Name: ';
        labels.appendChild(namelabel);

        var doselabel = document.createElement('p');
        doselabel.innerHTML = 'Drug Dosage: '
        labels.appendChild(doselabel);

        var startlabel = document.createElement('p');
        startlabel.innerHTML = 'Start Date: '
        labels.appendChild(startlabel);

        var endlabel = document.createElement('p');
        endlabel.innerHTML = 'End Date: '
        labels.appendChild(endlabel);

        var name = document.createElement('input');
        name.setAttribute('name', 'name');
        name.setAttribute('class', 'inputBoxes');
        name.setAttribute('type', 'text');
        inputs.appendChild(name);

        var dosage = document.createElement('input');
        dosage.setAttribute('name', 'dosage');
        dosage.setAttribute('type', 'text');
        dosage.setAttribute('class', 'inputBoxes');
        inputs.appendChild(dosage);

        var startDate = document.createElement('input');
        startDate.setAttribute('type', 'text');
        startDate.setAttribute('name', 'startDate');
        startDate.setAttribute('class', 'inputBoxes');
        inputs.appendChild(startDate);

        var endDate = document.createElement('input');
        endDate.setAttribute('name', 'endDate');
        endDate.setAttribute('type', 'text');
        endDate.setAttribute('class', 'inputBoxes');
        inputs.appendChild(endDate);

        var submitButton = document.createElement('input');
        submitButton.setAttribute('type', 'submit');
        labels.appendChild(submitButton);

        console.log(medications);
    }

    function addPractitioner() {
        var element = document.getElementById('Practitioners');
        
        var form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', '../../resources/addPractitioner.php');

        element.appendChild(form);


        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'practitionerEmail');
        
        var label = document.createElement('p');
        label.innerHTML = 'Practitioners NHS email: ';

        var submit = document.createElement('input');
        submit.setAttribute('type', 'submit');
        submit.setAttribute('value', 'Add Practitioner to list');

        form.appendChild(label);
        form.appendChild(input);
        form.appendChild(submit);
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
            FormData['Name'] = FormData['Name']['Text'];

            for (element in FormData) {
                if (element == 'Extension1') {

                    var newElement = document.createElement('li');
                    newElement.setAttribute('id', 'Practitioners');
                    profileElements.appendChild(newElement);

                    var title = document.createElement('h4');
                    title.innerHTML = 'Practitioners';

                    var image = document.createElement('input');
                    image.setAttribute('type', 'image');
                    image.setAttribute('onclick', 'addPractitioner()');
                    image.setAttribute('src', '../../Static/images/plus-button.png');
                    image.setAttribute('class', 'addMedication');

                    title.appendChild(image);

                    newElement.appendChild(title);

                    practitioners = FormData[element]['ValueCodeableConcept']

                    for (practitioner in practitioners) {
                        
                        var value = document.createElement('p');
                        value.innerHTML = (practitioners[practitioner]);
                        newElement.appendChild(value);

                        var minus = document.createElement('a');
                        var image = document.createElement('img');
                        image.setAttribute('src', '../../Static/images/minus-circular-button.png');
                        minus.setAttribute('href', ('../../resources/removePractitioner.php?practitioner=' + practitioners[practitioner] ))
                        image.setAttribute('class', 'addMedication')

                        value.appendChild(minus);
                        minus.appendChild(image);

                     }

                } else if (element == 'Extension2') {

                    var newElement = document.createElement('li');
                    newElement.setAttribute('class', 'medicationTitle');
                    profileElements.appendChild(newElement);

                    var title = document.createElement('h4');
                    title.setAttribute('class', 'patientMedication');
                    title.innerHTML = 'Patient Medication Changes';

                    var image = document.createElement('input');
                    image.setAttribute('type', 'image');
                    image.setAttribute('onclick', 'addMedication()');
                    image.setAttribute('src', '../../Static/images/plus-button.png');
                    image.setAttribute('class', 'addMedication')

                    title.appendChild(image);
                    newElement.appendChild(title);
                    

                    for (medication in FormData[element]['ValueCodeableConcept'] ) {
                        var value = document.createElement('text');
                        value.setAttribute('class', 'medicationText')
                        value.setAttribute('id', medication);

                        var edit = document.createElement('input');
                        edit.setAttribute('type', 'image');
                        edit.setAttribute('onclick', 'editMedication(' + medication + ')' );
                        edit.setAttribute('src', '../../Static/images/pencil-edit-button.png');
                        edit.setAttribute('class', 'editMedication');

                        if (!FormData[element]['ValueCodeableConcept'][medication][3]) {
                            FormData[element]['ValueCodeableConcept'][medication][3] = "Present";
                        }
                        value.innerHTML = (
                            "Drug Name:   " + medication + "</br>"
                            + "Dosage:    " + FormData[element]['ValueCodeableConcept'][medication][1] + "</br>"
                            + "Start Date: " + FormData[element]['ValueCodeableConcept'][medication][2] + "</br>"
                            +  "End Date: " + FormData[element]['ValueCodeableConcept'][medication][3]);
                        
                        newElement.appendChild(edit);
                        newElement.appendChild(value);
                    }
                
                } else if (element != '_id' && element != 'Name' && element != 'Extension0') {
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

        </script>
        	  
        <div class="footer"></div>
    </div>
</body>
</html>