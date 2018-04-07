function dataFormatting(variables, dataset){
	
	requiredData = [];

        for (var i = 0; i < dataset.length; i++) {
            requiredData[i] = {};
            requiredData[i]["x"] = new Date(dataset[i].DateTime);
            for (var j = 0; j < variables.length; j++) {
                requiredData[i][variables[j]] = dataset[i][variables[j]].Measure;
            };
        };
    sortByDate(requiredData);

    return(requiredData);
}

function getRange(dataset){
    var min = Number.POSITIVE_INFINITY;
    var max = Number.NEGATIVE_INFINITY;
    console.log(dataset);

    for(var i = 0; i < dataset.length; i++){
        for(attribute in dataset[i]){
            tmp = dataset[i][attribute];
            if (tmp < min) min = tmp;
            if (tmp > max && attribute != 'x') max = tmp;
        }
    }

    //give 10 points margin on y axis if minimum value isn't 0
    if (min != 0) {
        min = min-10;
    };
    
    return([min, max + 10]);
}

function sortByDate(dataset){

    dataset.sort(function(a, b){
      return a.x > b.x;
    });

    return dataset;
}