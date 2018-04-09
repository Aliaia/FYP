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
    <script src='../Js/createField.js'></script>

    <script type="text/javascript">
    function onLoad(){
       document.getElementById("userName").innerHTML = <?php print("'" . $_SESSION['login_user']['Name'] . "'") ?>;

    }

    function isItemInArray(array, item) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][0] == item[0] && array[i][1] == item[1]) {
                return true;
            }
        }
        return false;
    }

    function getUniqueAttributes(data){
        attributes = []
        for (element in data) {
            for (attribute in data[element]){         
                if (attribute != "_id" && attribute != "Identification" && attribute != "DateTime" ) {
                    var formAttribute = [attribute, data[element][attribute].Unit, typeof data[element][attribute].Measure]; 
                    attributes.push(formAttribute);
                };
                
            }
        }
        var filtered = [];
        for(item in attributes){
            if(isItemInArray(filtered, attributes[item]) == false){
                filtered.push(attributes[item]);
            }
        }

        return filtered;
    }

    function AddFormAttributes(){
        
        var formList = document.getElementById("formAttributes")

        //grey out form elements
        greyout();

        var newForm = document.createElement("form")
            newForm.setAttribute("class", "newForm");
            formList.appendChild(newForm);

        createListElement('Measurement Name', 'text', '', newForm);
        createListElement('Unit (optional)', 'text', '', newForm);

        var MeasurementType = document.createElement('li');
            MeasurementType.setAttribute('class', 'formElement');

        var submitButton = document.createElement('input');
            submitButton.setAttribute('type', 'submit');
            submitButton.setAttribute("value", 'Create Measurement');
            submitButton.setAttribute('class', 'submitButton');
            submitButton.setAttribute("onclick", 'return createNewField(this.form)')

        var discardButton = document.createElement('input');
            discardButton.setAttribute('type', 'button');
            discardButton.setAttribute("value", 'Discard Measurement');
            discardButton.setAttribute('class', 'submitButton');
            discardButton.setAttribute("onclick", 'return discardNewField()')

        
        //add list elements        
            newForm.appendChild(MeasurementType);
            newForm.appendChild(submitButton);
            newForm.appendChild(discardButton);


        var TypeLabel = document.createElement('label')
            TypeLabel.setAttribute('class', 'inputLabel');
            TypeLabel.textContent = 'Measurement Type';

        var Typeinput = document.createElement('select');
            Typeinput.setAttribute('class', 'formInput');
            Typeinput.setAttribute('name', 'MeasurementType');
            Typeinput.setAttribute('title', 'Measurement Type');
            Typeinput.innerHTML = "<option> Text </option><option> Number </option><option> Sliding Bar </option>"


        //Add all to list elements
            MeasurementType.appendChild(TypeLabel);
            MeasurementType.appendChild(Typeinput);

    }

    </script>

    <meta charset="UTF-8">

    <title>Condition Tracker</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/appStyle.css">

</head>
<body onload="onLoad()">
    <div class="container">

    	<h1>Condition Tracker</h1>
          <h2>An easier way to track health conditions</h2>

    </div>

    <nav class="navigation">
    	  	
      	<ul class="Nav-bar">
            <li><a href="profile.php" id="userName"></a></li>
            <li><a class="active" href="logAReading.php">Log a Reading</a></li>
            <li><a href="graphs.php">Graphs</a></li>
            <li><a href="personalData.php">Data</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="../UserLogin.php"> Log Out </a></li>
        </ul>
    </nav>
    
    <div class="content">	  
        <div class="title"> Log a Reading </div>
        <img class="help" src="../../Static/images/question-mark-button.png" title="Help">
        <input type="image" onclick="return AddFormAttributes()" class="plus" src="../../Static/images/plus-button.png" title="Add Measurement">

        <form class="inputForm">
            <ul id="formAttributes">

            </ul>
            <input type="Submit" class="submitButton" value="Submit Reading">
            <input type="Submit" class="submitButton" value="Discard Reading">

        </form>

        <script type="text/javascript">

            var FormData = <?php print_r(json_encode(getConditionData($_SESSION['login_user']['ID'])->toArray())); ?>;
            
            FormData = getUniqueAttributes(FormData);
            var formDiv = document.querySelector( "form" );
            var formList = document.getElementById("formAttributes")
            console.log(formList);

            for (attribute in FormData) {

                createListElement(FormData[attribute][0], FormData[attribute][2], FormData[attribute][1], formList);

            }
        
        </script>
    </div>


    <div class="footer"></div>
</body>
</html>