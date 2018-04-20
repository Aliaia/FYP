
//Creates a new field, adding to the list when a new field has been submitted
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

    var errorElement = document.getElementById('noData')

    if(errorElement) {
        errorElement.remove();
    }

	return false;
}

//If the user selects discard instead of save when creating a new field
function discardNewField() {

	removeNewForm();
    removeGreyOut();

    return false

}

//Creates a new li element in the list  of form elements
function createListElement(name, type, measureUnit, element){

    //create initial li element
	var divContainer = document.createElement('li');
        divContainer.setAttribute('class', 'formElement');
                
    element.appendChild(divContainer);

    //add label and input element
	var label = document.createElement('label');
        label.setAttribute('class', 'inputLabel');
        label.textContent = name;

    if (type !== 'text') {
        var input = document.createElement('input');
        input.setAttribute('class', 'formInput');
        input.setAttribute('name', (name + '[Measure]'));
        input.setAttribute('title', name);
        input.setAttribute('type', type);
    } else {
        var input = document.createElement('textarea');
        input.setAttribute('class', 'formInput');
        input.setAttribute('form', 'newForm');
        input.setAttribute('name', (name + '[Measure]'));
        input.setAttribute('title', name);

    }
        ;

    //create the hidden element which submits the unit
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

//greys out the list elements, and submit buttons
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


//removes the grey out on list elements and sbmit buttons
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

//removes the 'create new field' form on submission 
function removeNewForm() {
	var newForm = document.getElementsByClassName('newForm')
	console.log(newForm);
	newForm[0].remove();

}

function formatValues(){
    //TODO: add form processing

	return true;
}

//checks to see if the item is in the 2D array
function isItemInArray(array, item) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][0] == item[0] && array[i][1] == item[1]) {
            return true;
        }
    }
    return false;
};

//runs through all the data, and finds all the unique attributes not including Dates, and user identifications.
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