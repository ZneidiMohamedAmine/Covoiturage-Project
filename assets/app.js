import { registerReactControllerComponents } from '@symfony/ux-react';
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


const App = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(false); // Example state for authentication
  const [user, setUser] = useState(null); // State to store user data

  useEffect(() => {
    // Example: Check authentication status or fetch user data from session
    // Replace with actual authentication logic
    const userFromSession = {
      name: 'John Doe',
      email: 'john.doe@example.com',
      phone: '123-456-7890',
      bio: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    };

    setUser(userFromSession);
    setIsLoggedIn(!!userFromSession); // Update isLoggedIn based on user session
  }, []);

  const handleLogout = (e) => {
    e.preventDefault();
    // Example: Perform logout logic (clear session, redirect, etc.)
    setUser(null);
    setIsLoggedIn(false);
  };

  const handleLogin = (username, password) => {
    // Example: Perform login logic (API call, validation, etc.)
    console.log('Logging in with:', username, password);
    // Simulate successful login
    setUser({
      name: 'John Doe',
      email: 'john.doe@example.com',
      phone: '123-456-7890',
      bio: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    });
    setIsLoggedIn(true);
  };

  return (
    <div className="app">
      <div className="header">
        {isLoggedIn ? (
          <form onSubmit={handleLogout}>
            <button type="submit">Logout</button>
          </form>
        ) : (
          <>
            <LoginForm onLogin={handleLogin} />
            <form action="/register" method="POST">
              <button type="submit" value="register">Register</button>
            </form>
          </>
        )}
      </div>

      {isLoggedIn && <ProfilePage user={user} />}
    </div>
  );
};

export default App;



registerReactControllerComponents();

// webpack.config.js
const path = require('path');

module.exports = {
  entry: './src/index.js',  // Assuming this is your entry file where App.js is imported
  output: {
    path: path.resolve(__dirname, 'public/build'),
    filename: 'bundle.js',
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
        },
      },
    ],
  },
  resolve: {
    extensions: ['.js', '.jsx'],
  },
};
