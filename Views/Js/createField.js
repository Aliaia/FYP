function createNewField(data){
	var name = data.MeasurementName.value;
	var measureUnit = data.Unit.value;
	var type = data.MeasurementType.value;

	if(name != ""){
		var formList = document.getElementById("formAttributes")

		createListElement(name, type, measureUnit, formList);
	}
	
    removeNewForm();
    removeGreyOut();

	return false;
}

function discardNewField() {

	removeNewForm();
    removeGreyOut();

    return false;

}

function createListElement(name, type, measureUnit, element){

	var divContainer = document.createElement('li');
        divContainer.setAttribute('class', 'formElement');
                
    element.appendChild(divContainer);

	var label = document.createElement('label');
        label.setAttribute('class', 'inputLabel');
        label.textContent = name;

    var input = document.createElement('input');
        input.setAttribute('class', 'formInput');
        input.setAttribute('name', name);
        input.setAttribute('title', name);
        input.setAttribute('type', type);
    
    var unit = document.createElement('text');
        unit.setAttribute('class', 'formUnit');
        unit.textContent = measureUnit;

    var br = document.createElement('br');
    
    divContainer.appendChild(label);
    divContainer.appendChild(input);
    divContainer.appendChild(unit)
    divContainer.appendChild(br);

}

function greyout(){

	var listElements = document.getElementsByClassName('formElement')
        var SubmitElements = document.getElementsByClassName('submitButton')

        //grey out buttons
        for (element in SubmitElements){
            if (SubmitElements[element].nodeName =="INPUT") {
                SubmitElements[element].setAttribute('disabled', 'disabled');
            };
        }

        //grey out form elements
        for (element in listElements){   
            if(listElements[element].nodeName =="LI"){
                var input = listElements[element].querySelector('input');
                input.setAttribute('disabled', 'disabled');
            }
        }
}

function removeGreyOut(){

	var listElements = document.getElementsByClassName('formElement')
        var SubmitElements = document.getElementsByClassName('submitButton')

        //grey out buttons
        for (element in SubmitElements){
            if (SubmitElements[element].nodeName =="INPUT") {
                SubmitElements[element].removeAttribute('disabled');
            };
        }

        //grey out form elements
        for (element in listElements){   
            if(listElements[element].nodeName =="LI"){
                var input = listElements[element].querySelector('input');
                input.removeAttribute('disabled');
            }
        }
}


function removeNewForm() {
	var newForm = document.getElementsByClassName('newForm')
	console.log(newForm);
	newForm[0].remove();
	// console.log(newForm);
}