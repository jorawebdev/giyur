import { combineReducers } from 'redux'
import login from './login'
import count from './count'

const CombReducers = combineReducers({
  login,
  count
})

export default CombReducers;
