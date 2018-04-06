function dataFormatting(variable, dataset){
	
	requiredData = [];

        for (var i = 0; i < dataset.length; i++) {
            if(dataset[i].hasOwnProperty(variable)){

                console.log(new Date(dataset[i].DateTime));
            	
            	requiredData.push({
            		y: dataset[i][variable].Measure,
            		x: new Date(dataset[i].DateTime)
            	})
            
            }
        };

    requiredData = sortByDate(requiredData);

    return requiredData;
}

function sortByDate(dataset){

    dataset.sort(function(a, b){
      return a.x > b.x;
    });

    console.log(dataset);


    return dataset;
}