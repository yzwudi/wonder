var chart = c3.generate({
    data: {
        //x: 'x',
        columns: columns,
        type: 'spline'
    },
    axis: {
        x: {
            type: 'category',
            categories: categories,
            tick: {
                format: '%d'
            }
        }
    }
});

/*var chart2 = c3.generate({
    data: {
        x: 'x',
        columns: columns,
        type: 'spline'
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%d'
            }
        }
    }
});*/



