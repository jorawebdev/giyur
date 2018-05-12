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
              <Routes {...this.props} />
          </div>
      );
  }
}

export default App;
