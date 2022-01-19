var data = [
    {title: "Low", percent: 21, amount: '$2000'},
    {title: "Offer 1", percent: 31, amount: '$3000'},
    {title: "Offer 2", percent: 22, amount: '$5000'},
    {title: "High", percent: 32, amount: '$10000'},
];

var margin = {top: 0, right: 0, bottom: 30, left: 32},
    width = 300,
    height = 145;

var formatPercent = d3.format("1");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(formatPercent)
    .tickValues(d3.range(0, d3.max(data, function(d) { return d.percent + 10; }), 10));

var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([-10, 0])
    .html(function(d) {
        return "Current " + d.title + '<span class="percent-sign"></span> :  ' + d.amount + "<br/>Current " + d.title + '<span class="percent-sign">%</span> : ' + d.percent + '%';
    })

var svg = d3.select("#chartContainer").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

x.domain(data.map(function(d) { return d.title; }));
y.domain([0, d3.max(data, function(d) { return d.percent + 10; })]);

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);

svg.append("g")
    .attr("class", "y axis")
    .call(yAxis)
    .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end");

svg.selectAll(".bar")
    .data(data)
    .enter().append("rect")
    .attr("class", "bar")
    .attr("x", function(d) { return x(d.title) + 25; })
    .attr("width", 18)
    .attr("y", function(d) { return y(d.percent); })
    .attr("height", function(d) { return height - y(d.percent); })
    .on('mouseover', tip.show)
    .on('mouseout', tip.hide)


// Price Per Acre Chart
var data2 = [
    {title: "5 to 9", amount: 12000},
    {title: "10 to 19", amount: 10000},
    {title: "20 to 39", amount: 9000},
    {title: "40 to 99", amount: 8000},
    {title: "100 +", amount: 7000},
];

var margin2 = {top: 22, right: 0, bottom: 30, left: 53},
    width2 = 422,
    height2 = 212;

var formatPercent2 = d3.format("1");

var x2 = d3.scale.ordinal()
    .rangeRoundBands([0, width2], .1);

var y2 = d3.scale.linear()
    .range([height2, 0]);

var xAxis2 = d3.svg.axis()
    .scale(x2)
    .orient("bottom");

var yAxis2 = d3.svg.axis()
    .scale(y2)
    .orient("left")
    .tickFormat(formatPercent2)
    .tickValues(d3.range(0, d3.max(data2, function(d) { return d.amount + 1000; }), 1000));

var tip2 = d3.tip()
    .attr('class', 'd3-tip text-center')
    .offset([-10, 0])
    .html(function(d) {
        return d.title + ' Acres<br/>$' + d.amount;
    })

var svg2 = d3.select("#acre-chart").append("svg")
    .attr("width", width2 + margin2.left + margin2.right)
    .attr("height", height2 + margin2.top + margin2.bottom)
    .append("g")
    .attr("transform", "translate(" + margin2.left + "," + margin2.top + ")");

svg2.call(tip2);

x2.domain(data2.map(function(d) { return d.title; }));
y2.domain([d3.min(data2, function(d) { return d.amount - 1000; }), d3.max(data2, function(d) { return d.amount + 10; })]);

svg2.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height2 + ")")
    .call(xAxis2);

svg2.append("g")
    .attr("class", "y axis")
    .call(yAxis2)
    .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end");

svg2.selectAll(".bar")
    .data(data2)
    .enter().append("rect")
    .attr("class", "bar")
    .attr("x", function(d) { return x2(d.title) + 25; })
    .attr("width", 28)
    .attr("y", function(d) { return y2(d.amount); })
    .attr("height", function(d) { return height2 - y2(d.amount); })
    .on('mouseover', tip2.show)
    .on('mouseout', tip2.hide)




$(document).ready(function () {

    $('#chartContainer .y.axis .tick line, #acre-chart .y.axis .tick line').attr({
        x1: '100%',
        x2: 0,
    });

    $('#chartContainer .y.axis .tick text').append('%');
    $('#acre-chart .y.axis .tick text').prepend('$');
    $('#acre-chart .x.axis .tick text').append('<span>Acre</span>');

    // $('#chartContainer svg').css({
    //     width: '100%',
    //     minWidth: 332,
    //     height: $('#chartContainer svg').width() * .52
    // });

    $("#chartContainer rect").mouseout(function () {
        $('.d3-tip').css('left', 0)
    })
});
