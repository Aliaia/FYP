//puts data into the correct layout for the create graph function.
//sorts the data into time for when setting time is implemented in log a reading.
function dataFormatting(variables, dataset){
	
	requiredData = [];

        for (var i = 0; i < dataset.length; i++) {
            requiredData[i] = {};
            requiredData[i]["Date"] = new Date(dataset[i].DateTime);
            for (var j = 0; j < variables.length; j++) {
                if (dataset[i][variables[j]]) {
                    requiredData[i][variables[j]] = dataset[i][variables[j]].Measure;
                };
            };
        };
    
    sortByDate(requiredData);
    return(requiredData);
}


//finds minimum and maximum values of variable for y axis.
//add a 10 point margin to maximum and minimum
function getRange(dataset){
    var min = Number.POSITIVE_INFINITY;
    var max = Number.NEGATIVE_INFINITY;

    for(var i = 0; i < dataset.length; i++){
        for(attribute in dataset[i]){
            tmp = dataset[i][attribute];
            if (tmp < min) min = tmp;
            if (tmp > max && attribute != 'Date') max = tmp;
        }
    }

    //give 10 points margin on y axis if minimum value isn't 0
    if (min != 0) {
        min = min-10;
    };
    
    return([min, max + 10]);
}

//sorts the data given by date 
function sortByDate(dataset){

    dataset.sort(function(a, b){
      return a.Date > b.Date;
    });

    return dataset;
}