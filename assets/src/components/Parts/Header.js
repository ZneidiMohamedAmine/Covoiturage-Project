// Header.js
import React from 'react';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const Header = ({ isAuthenticated }) => {
  const navigate = useNavigate();
  const [searchQuery, setSearchQuery] = useState('');

  const handleLogout = async () => {
    // Implement logout logic here
    // Redirect to home page after logout
    navigate('/');
  };

  const handleLogin = () => {
    navigate('/login');
  };

  const handleRegister = () => {
    navigate('/register');
  };

  const handleControlPanel = () => {
    navigate('/control-panel');
  };

  const handleSearch = (event) => {
    event.preventDefault();
    // Implement search logic here
  };

  return (
    <header style={{ display: 'flex', justifyContent: 'flex-end', alignItems: 'center', padding: '10px', backgroundColor: '#f8f9fa' }}>
      {isAuthenticated ? (
        <>
          <button onClick={handleLogout} className="btn btn-danger">Logout</button>
          <button onClick={handleControlPanel} className="btn btn-success">Control Panel</button>
        </>
      ) : (
        <>
          <button onClick={handleLogin} className="btn btn-primary">Login</button>
          <button onClick={handleRegister} className="btn btn-success">Register</button>
          <button onClick={handleControlPanel} className="btn btn-success">Control Panel</button>
        </>
      )}
      <div className="search-container" style={{ marginTop: '20px', marginBottom: '20px', padding: '20px', backgroundColor: '#f8f9fa', borderRadius: '8px' }}>
        <form onSubmit={handleSearch} className="form-inline">
          <input
            type="text"
            name="query"
            className="form-control mr-sm-2"
            placeholder="Search..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
          />
          <button type="submit" className="btn btn-outline-primary">Search</button>
        </form>
      </div>
    </header>
  );
};

export default Header;
