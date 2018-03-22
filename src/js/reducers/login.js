const initialState = {
    isLoggedIn: false,
    name: '',
    email: '',
    role: ''
};

const login = (state = initialState, action) => {
  //console.log('in login reducer', state, action);
  switch (action.type) {
      case 'LOGIN':
          return Object.assign({}, state, {
              isLoggedIn: true,
              name: action.name,
              role: action.role,
          });
      case 'LOGOUT':
          return Object.assign({}, state, {
              isLoggedIn: false,
              name: '',
              role: ''
          });
      default:
          return state;
  }
}
export default login
