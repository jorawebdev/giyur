import React, {Component} from 'react'
import Header from './pages/header'
import Routes from './routes'

class App extends Component {
  constructor(props) {
      super(props)
  }
  render() {
      //console.log('in index.js: ', this.props);
      return (
          <div className="App">
              <Header {...this.props} />
              <div id="heroBox" className="d-flex justify-content-center"><h1 className="col-4 align-self-center">Giyur: your DATA VISUALIZATION</h1></div>
              <Routes {...this.props} />
          </div>
      );
  }
}

export default App;
