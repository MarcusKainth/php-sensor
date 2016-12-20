             // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {
                'packages': ['corechart']
            });
             // Set a callback to run when the Google Visualization API is loaded.
             // google.setOnLoadCallback(drawChart);

            function ordinal_suffix_of(i) {
                var j = i % 10,
                    k = i % 100;
                if (j == 1 && k != 11) {
                    return i + "st";
                }
                if (j == 2 && k != 12) {
                    return i + "nd";
                }
                if (j == 3 && k != 13) {
                    return i + "rd";
                }
                return i + "th";
            }

            function drawChart(year, month, day, hour, minute) {
                var jsonData = $.ajax({
                    url: "includes/temp.json.php",
                    dataType: "json",
                    async: false
                }).responseText;
                // Create our data table out of JSON data loaded from server.
                var data = new google.visualization.DataTable(jsonData);
                var options = {
                    hAxis: {
                        viewWindow: {
                            min: new Date(year, month, day, hour, minute - 58),
                            max: new Date(year, month, day, hour, minute + 2)
                        },
                        gridlines: {
                            count: -1,
                            units: {
                                days: {
                                    format: ['MMM dd']
                                },
                                hours: {
                                    format: ['HH:mm', 'ha']
                                },
                            },
                            color: '#e0e0e0'
                        },
                        minorGridlines: {
                            units: {
                                hours: {
                                    format: ['hh:mm:ss a', 'ha']
                                },
                                minutes: {
                                    format: ['HH:mm a Z', 'mm']
                                }
                            }
                        },
                        title: 'Time',
                        textStyle: {
                            color: '#212121',
                            fontName: 'Roboto'
                        },
                        titleTextStyle: {
                            color: '#212121',
                            fontName: 'RobotoDraft',
                            italic: false,
                        },
                    },
                    vAxis: {
                        title: 'Temperature (°C)',
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            max: 35,
                            min: 0
                        },
                        gridlines: {
                            count: 5,
                            color: '#e0e0e0'
                        },
                        baselineColor: '#212121',
                        textStyle: {
                            color: '#212121',
                            fontName: 'Roboto'
                        },
                        titleTextStyle: {
                            color: '#212121',
                            fontName: 'RobotoDraft',
                            italic: false,
                        },
                    },
                    'height': 350,
                    series: {
                        0: { color: '#009688' },
                    },
                    legend: {
                        position: 'none',
                    },
                    chartArea: {
                        top: '15px',
                        left: '10px',
                        height: '75%',
                        width: '90%'
                    },
                    curveType: 'function',
                    titleTextStyle: {
                        color: '#212121',
                        fontName: 'Roboto'
                    },
                };
                // Instantiate and draw our chart, passing in some options.
                // Do not forget to check your div ID
                var chart = new google.visualization.LineChart(document.getElementById(
                    'temp_chart'));
                chart.draw(data, options);
            }
