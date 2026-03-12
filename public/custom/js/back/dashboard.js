jQuery(function(){
    // Chart data definitions
    var stateData = [
        {label: "AZ", data: 27, color: "#3c8dbc"},
        {label: "CA", data: 30, color: "#0073b7"},
        {label: "CO", data: 33, color: "#dd4b39"},
        {label: "UT", data: 10, color: "#f39c12"}
    ];
    var divisionData = [
        {label: "Divi1", data: 10, color: "#f56954"},
        {label: "Divi2", data: 20, color: "#f39c12"},
        {label: "Divi3", data: 25, color: "#00a65a"},
        {label: "Divi4", data: 15, color: "#3c8dbc"},
        {label: "Divi5", data: 20, color: "#0073b7"},
        {label: "Divi6", data: 10, color: "#00c0ef"}
    ];
    var typeData = [
        {label: "CF", data: 30, color: "#3c8dbc"},
        {label: "HB", data: 20, color: "#0073b7"},
        {label: "NH", data: 50, color: "#00c0ef"},
        {label: "LAB", data: 50, color: "#999999"}
    ];
    var modalityData = [
        {label: "Xray", data: 40, color: "#3c8dbc"},
        {label: "US", data: 20, color: "#0073b7"},
        {label: "EKG", data: 50, color: "#00c0ef"},
        {label: "LAB", data: 40, color: "#485c7d"}
    ];

    var pieOptions = {
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
        legend: { show: true }
    };

    // Track which charts have been rendered
    var rendered = {};

    function renderChart(selector, data) {
        if (!rendered[selector] && jQuery(selector).is(':visible') && jQuery(selector).width() > 0) {
            jQuery.plot(selector, data, pieOptions);
            rendered[selector] = true;
        }
    }

    // Render charts when their collapsed box is expanded
    jQuery(document).on('expanded.boxwidget', function(e) {
        setTimeout(function() {
            renderChart("#chart_order_state", stateData);
            renderChart("#chart_order_division", divisionData);
            renderChart("#chart_order_type", typeData);
            renderChart("#chart_order_modality", modalityData);
        }, 100);
    });

    // Also try rendering any that are already visible on load
    renderChart("#chart_order_state", stateData);
    renderChart("#chart_order_division", divisionData);
    renderChart("#chart_order_type", typeData);
    renderChart("#chart_order_modality", modalityData);
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

