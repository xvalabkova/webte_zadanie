 //----------------------------------------------------------------------------
// not working withou API key
 if(getCookie("ValidUser")!="true")
  throw new Error("my error message");

  //----------------------------------------------------------------------------

'use strict';

const plotCanvas = document.querySelector('#plot-canvas');
const animationCanvas = document.querySelector('#animation-canvas');
const plotCheck = document.querySelector('#plot');
const animationCheck = document.querySelector('#animation');
const sendBtn = document.querySelector('#sendBtn');

sendBtn.addEventListener('click', () => {
    if (plotCheck.checked) {
        const data = new FormData(document.querySelector('#param-form'));
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
                    createPlot(arrayTime, arrayY, result.c);

                    plotCanvas.classList.remove('hidden');
                }
                //else
            })
    }
});

const createPlot = (x, y, c) => {
    let dataX = [x[2]];
    let dataY = [y[2]];

    let trace1 = {
        x: dataX,
        y: dataY,
        name: 'Placeholder',
        mode: 'lines',
        type: 'scatter',
        line: {
            color: '#17BECF'
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
            range: [-6, 6],
            title: 'Y',
        },
    }

    let config = {
        displayModeBar: true,
        scrollZoom: true,
        responsive: true,
        doubleClickDelay: 1000,
    }

    Plotly.newPlot(plotCanvas, [trace1], layout, config);

    let i = 3;
    let interval = setInterval(() => {
        if (!x[i] || !y[i]) {
            clearInterval(interval);
        }
        dataX.push(x[i++]);
        dataY.push(y[i++]);

        Plotly.redraw(plotCanvas, [trace1], layout, config);

    }, c);
}