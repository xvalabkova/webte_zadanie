'use strict';

const plotCanvas = document.querySelector('#plot-canvas');
const animationCanvas = document.querySelector('#animation-canvas');
const plotCheck = document.querySelector('#plot');
const animationCheck = document.querySelector('#animation');
const sendBtn = document.querySelector('#sendBtn');
const warn = document.querySelector('#warning-message');
let height = null;

sendBtn.addEventListener('click', () => {
    if (plotCheck.checked) {
        const data = new FormData(document.querySelector('#param-form'));
        height = Math.abs(data.get('r') * 2);
        fetch("services/getPlotData.php", {
                method: "POST",
                body: JSON.stringify({
                    m1: data.get('m1'),
                    m2: data.get('m2'),
                    r: data.get('r')
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.y != "Error transpired") {
                    let arrayY = (Object.values(result.y));
                    let arrayTime = (Object.values(result.t));
                    let arrayY2 = (Object.values(result.x1));
                    createPlot(arrayTime, arrayY, result.c, arrayY2);

                    warn.classList.add('hidden');
                    plotCanvas.classList.remove('hidden');
                } else {
                    warn.classList.remove('hidden');
                    warn.innerHTML = (result.lang == 'sk') ? "Chyba pri kalkulovaní hodnôt" : "Error occurred during the calculation of values";
                }
            })
    }
});

const createPlot = (x, y, c, y2) => {
    let dataX = [x[2]];
    let dataY = [y[2]];
    let dataY2 = [y2[2]];

    let trace1 = {
        x: dataX,
        y: dataY,
        name: 'Car',
        mode: 'lines',
        type: 'scatter',
        line: {
            color: '#17BECF'
        }
    };

    let trace2 = {
        x: dataX,
        y: dataY2,
        name: 'Wheel',
        mode: 'lines',
        type: 'scatter',
        line: {
            color: '#151243'
        }
    };

    let layout = {
        title: 'Placeholder II',
        showlegend: true,
        xaxis: {
            range: [-0.5, 5.5],
            title: 'X'
        },
        yaxis: {
            range: [-height, height],
            title: 'Y',
        },
    }

    let config = {
        displayModeBar: true,
        scrollZoom: true,
        responsive: true,
        doubleClickDelay: 1000,
    }

    Plotly.newPlot(plotCanvas, [trace1, trace2], layout, config);

    let i = 3;
    let interval = setInterval(() => {
        if (!x[i] || !y[i] || !y2[i]) {
            clearInterval(interval);
        }
        dataX.push(x[i++]);
        dataY.push(y[i++]);
        dataY2.push(y2[i++]);

        Plotly.redraw(plotCanvas, [trace1, trace2], layout, config);

    }, c);
}