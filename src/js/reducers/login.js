const initialState = {
    isLoggedIn: false,
    name: '',
    email: '',
    profile: '',
    role: ''
};

const login = (state = initialState, action) => {
  //console.log('in login reducer', state, action);
  switch (action.type) {
      case 'LOGIN':
          return Object.assign({}, state, {
              isLoggedIn: true,
              profile: action.uProfile,
              role: action.role,
          });
      case 'LOGOUT':
          return Object.assign({}, state, {
              isLoggedIn: false,
              profile: '',
              role: ''
          });
      default:
          return state;
  }
}
export default login
