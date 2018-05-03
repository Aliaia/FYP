
//Creates a new field, adding to the list when a new field has been submitted
function createNewField(data){
	var name = data['Measurement Name[Measure]'].value;
	var measureUnit = data['Unit (optional)[Measure]'].value;
	var type = data.MeasurementType.value;
	if (name) {
		var formList = document.getElementById("formAttributes")

		createListElement(name, type, measureUnit, formList, 'createData');
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

    var confirm = window.confirm("Are you sure you want to discard this field?");

    if (confirm) {

	    removeNewForm();
        removeGreyOut();
        return false
    }

    return true;

}

//Creates a new li element in the list  of form elements
function createListElement(name, type, measureUnit, element, formName){

    //create initial li element
	var divContainer = document.createElement('li');
        divContainer.setAttribute('class', 'formElement');
                
    element.appendChild(divContainer);

    //add label and input element
	var label = document.createElement('label');
        label.setAttribute('class', 'inputLabel');
        label.textContent = name;

    if (type !== 'text' && type !== 'Text') {
        var input = document.createElement('input');
        input.setAttribute('class', 'formInput');
        input.setAttribute('name', (name + '[Measure]'));
        input.setAttribute('title', name);
        input.setAttribute('type', type);
    } else {
        var input = document.createElement('textarea');
        input.setAttribute('class', 'formInput');
        input.setAttribute('form', formName);
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

	var listElements = document.getElementsByClassName('formElement');
    var SubmitElements = document.getElementsByClassName('submitButton');
    var plusButton = document.getElementsByClassName('plus')[0];
    plusButton.setAttribute('disabled', 'disabled');
    plusButton.setAttribute('src', '../../Static/images/plus-button-grey.png')

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

	var listElements = document.getElementsByClassName('formElement');
    var SubmitElements = document.getElementsByClassName('submitButton');
    var plusButton = document.getElementsByClassName('plus')[0];
    plusButton.removeAttribute('disabled');
    plusButton.setAttribute('src', '../../Static/images/plus-button.png')

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
	newForm[0].remove();

}

//finds form elements and if there's atleast one value added to the form, it'll submit
function formatValues(data){
    var hasValues = false;
    for (attribute in data){
        if (
        typeof data[attribute] == 'object' && 
        data[attribute] != null && 
        (data[attribute].nodeName == 'INPUT' || data[attribute].nodeName == 'TEXTAREA') &&
        data[attribute].type != 'hidden' && 
        data[attribute].type != 'submit') 
        {
            if(data[attribute].value != ""){
                hasValues = 'true'
                return true
            };
        };
    }
    var error = document.getElementsByClassName('fieldError')[0];
    console.log(error);
    error.innerHTML = "No data has been entered";
    return false;
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


//If a user clicks discard, they're asked if they're sure first.
function discardReading() {
    var confirm = window.confirm("Are you sure you want to discard your reading?");

    if(confirm) {
        window.location.href = '../App/graphs.php';
    }
    return false;
}