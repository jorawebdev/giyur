import React, { Component } from 'react';
import {  XYPlot,
  ArcSeries,
  XAxis,
  YAxis} from 'react-vis';

class App extends Component {
  render() {
    const PI = Math.PI;
    const myData = [
      {angle0: 0, angle: Math.PI / 4, opacity: 0.2, radius: 2, radius0: 1},
      {angle0: PI / 4, angle: 2 * PI / 4, radius: 3, radius0: 0},
      {angle0: 2 * PI / 4, angle: 3 * PI / 4, radius: 2, radius0: 0},
      {angle0: 3 * PI / 4, angle: 4 * PI / 4, radius: 2, radius0: 0},
      {angle0: 4 * PI / 4, angle: 5 * PI / 4, radius: 2, radius0: 0},
      {angle0: 0, angle: 5 * PI / 4, radius: 1.1, radius0: 0.8}
    ]

    return (
      <div className="App">
      <XYPlot
        xDomain={[-5, 5]}
        yDomain={[-5, 5]}
        width={300}
        height={300}>
        <ArcSeries
          animation
          radiusType={'literal'}
          center={{x: -2, y: 2}}
          data={myData}
          colorType={'literal'}/>
      </XYPlot>
      </div>
    );
  }
}

export default App;
