<!doctype html>
<html lang="en">
<head>

    <?php 
        
        session_start();
        
        if (isset($_SESSION['login_user']) == false) {
            header("Location: ../ProfessionalLogin.php");
        }

        include '../../resources/config.php';
    ?>

    <script type="text/javascript">

            var patientList = <?php print_r(json_encode(getPatients($_SESSION['login_user']['ID']))) ?>;

    </script>

    <link rel="stylesheet" href="../css/appStyle.css"> <!-- custom styles -->

</head>
<body>
    <div class="container">

    	<h1>Condition Tracker</h1>
          <h2>An easier way to track health conditions</h2>

    </div>

    <div class="container2">

        <nav class="navigation">
        	  	
          	<ul class="Nav-bar">
                <li><a href="patients.php" class="active">Patients List</a></li>
                <li><a href="../../resources/logout.php"> Log Out </a></li>
            </ul>
        </nav>
        
        <div class="content">     
            <div class="title"> Patient List </div>
            <img class="help" src="../../Static/images/question-mark-button.png" title="Help">
        </div>
        <div class="page">
            <table class="patientTable">
                <tr>
                    <th> Patient Name </th>
                    <th> Date Of Birth </th>
                </tr>
            </table>

        </div>

        <script type="text/javascript">
            var table = document.getElementsByClassName('patientTable')[0];
            for (var i = patientList.length - 1; i >= 0; i--) {
                var tr = document.createElement('tr');
                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var patientName = document.createTextNode(patientList[i][0]);
                var patientBirthday = document.createTextNode(patientList[i][1]);

                tr.setAttribute("onclick", ("location.href='patientProfile.php?id=" + patientList[i][2]['$oid'] + "'"));
                
                td1.appendChild(patientName);
                td2.appendChild(patientBirthday);

                tr.appendChild(td1);
                tr.appendChild(td2)

                table.appendChild(tr);
            };
        </script>

        <div class="footer"></div>
    </div>
</body>
</html>