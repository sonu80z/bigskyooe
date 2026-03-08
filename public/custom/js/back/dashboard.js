jQuery(function(){
    // order by state chat   AZ, CA, CO, UT
    var donutData = [
        {label: "AZ", data: 27, color: "#3c8dbc"},
        {label: "CA", data: 30, color: "#0073b7"},
        {label: "CO", data: 33, color: "#dd4b39"},
        {label: "UT", data: 10, color: "#f39c12"}
    ];
    jQuery.plot("#chart_order_state", donutData, {
        series: {
            pie: {
                show: true,
                radius: 1,
                innerRadius: 0,
                label: {
                    show: true,
                    radius: 2 / 3,
                    formatter: labelFormatter,
                    threshold: 0.1
                }
            }
        },
        legend: {
            show: true
        }
    });
    // Orders by Division
    var donutData = [
        {label: "Divi1", data: 10, color: "#f56954"},
        {label: "Divi2", data: 20, color: "#f39c12"},
        {label: "Divi3", data: 25, color: "#00a65a"},
        {label: "Divi4", data: 15, color: "#3c8dbc"},
        {label: "Divi5", data: 20, color: "#0073b7"},
        {label: "Divi6", data: 10, color: "#00c0ef"}
    ];
    jQuery.plot("#chart_order_division", donutData, {
        series: {
            pie: {
                show: true,
                radius: 1,
                innerRadius: 0,
                label: {
                    show: true,
                    radius: 2 / 3,
                    formatter: labelFormatter,
                    threshold: 0.1
                }
            }
        },
        legend: {
            show: true
        }
    });

    // Orders by Order Type  CF, HB, NH, LAB
    var donutData = [
        {label: "CF", data: 30, color: "#3c8dbc"},
        {label: "HB", data: 20, color: "#0073b7"},
        {label: "NH", data: 50, color: "#00c0ef"},
        {label: "LAB", data: 50, color: "#999999"}
    ];
    jQuery.plot("#chart_order_type", donutData, {
        series: {
            pie: {
                show: true,
                radius: 1,
                innerRadius: 0,
                label: {
                    show: true,
                    radius: 2 / 3,
                    formatter: labelFormatter,
                    threshold: 0.1
                }
            }
        },
        legend: {
            show: true
        }
    });
    // Order by Modality  Xray,  US,  EKG, LAB
    var donutData = [
        {label: "Xray", data: 40, color: "#3c8dbc"},
        {label: "US", data: 20, color: "#0073b7"},
        {label: "EKG", data: 50, color: "#00c0ef"},
        {label: "LAB", data: 40, color: "#485c7d"}
    ];
    jQuery.plot("#chart_order_modality", donutData, {
        series: {
            pie: {
                show: true,
                radius: 1,
                innerRadius: 0,
                label: {
                    show: true,
                    radius: 2 / 3,
                    formatter: labelFormatter,
                    threshold: 0.1
                }
            }
        },
        legend: {
            show: true
        }
    });

});

function labelFormatter(label, series) {
    /*return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
        + label
        + "<br>"
        + Math.round(series.percent) + "%</div>";
        */
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
        + label
        + "<br>"
        + series.data[0][1] + "</div>";
}

