import React, { useState } from 'react';
import './styles.css';

const LoginForm = ({ onLogin }) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    
    // Example: Validate inputs (can add more validation as needed)
    if (!username || !password) {
      setError('Please enter username and password.');
      return;
    }

    // Call parent component's onLogin function
    onLogin(username, password);

    // Clear form inputs and errors after submission
    setUsername('');
    setPassword('');
    setError('');
  };

  return (
    <div className="form-container">
      <h2>Login</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="text"
          name="username"
          placeholder="Username"
          value={username}
          onChange={(e) => setUsername(e.target.value)}
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        {error && <div>{error}</div>}
        <button type="submit">Login</button>
      </form>
    </div>
  );
};

export default LoginForm;
