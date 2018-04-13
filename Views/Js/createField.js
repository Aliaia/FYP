function createNewField(data){
	var name = data['Measurement Name[Measure]'].value;
	var measureUnit = data['Unit (optional)[Measure]'].value;
	var type = data.MeasurementType.value;

	if (name) {
		var formList = document.getElementById("formAttributes")

		createListElement(name, type, measureUnit, formList);
	};
	
    removeNewForm();
    removeGreyOut();

	return false;
}

function discardNewField() {

	removeNewForm();
    removeGreyOut();

    return false

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
        input.setAttribute('name', (name + '[Measure]'));
        input.setAttribute('title', name);
        input.setAttribute('type', type);

    // var inputName = name.concat('[0]')

    var inputHidden = document.createElement('input');
    	inputHidden.setAttribute('type', 'hidden');
    	inputHidden.setAttribute('value', measureUnit); 
    	inputHidden.setAttribute('name', (name + '[Unit]'))
    
    var unit = document.createElement('text');
        unit.setAttribute('class', 'formUnit');
        unit.textContent = measureUnit;

    var br = document.createElement('br');
    
    divContainer.appendChild(label);
    divContainer.appendChild(input);
    divContainer.appendChild(inputHidden);
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

}

function formatValues(){
    //add form processing

	return true;
}

function isItemInArray(array, item) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][0] == item[0] && array[i][1] == item[1]) {
            return true;
        }
    }
    return false;
};

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