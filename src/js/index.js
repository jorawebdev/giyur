import React, {Component} from 'react';
//import './App.css';
import Chart from './components/chart'
import Plot from './components/plot'
import Arc from './components/arc'

const API_URL = "https://nataliia-radina.github.io/react-vis-example/";

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {
            results: [],
        };
    }

    componentDidMount() {
      console.log('mounted');
        fetch(API_URL)
            .then(response => {
                if (response.ok) {
                    return  response.json()
                }
                else {
                    throw new Error ('something went wrong')
                }
            })
            .then(response => this.setState({
                results: response.results.filter((r)=>{
                        return r.name === 'JavaScript';
                    })
                })
            )}

    render() {
        const {results} = this.state;

        return (
            <div className="App">
                <Chart data={results}/>
                <Plot />
                <Arc />
            </div>
        );
    }
}

export default App;
