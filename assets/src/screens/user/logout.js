import React, { useEffect } from 'react';

const Logout = () => {
  useEffect(() => {
    const handleLogout = async () => {
      try {
        // Remove token from localStorage
        localStorage.removeItem('jwtToken');

        // Perform the logout action
        const response = await fetch('/api/logout', { method: 'POST' });
        if (!response.ok) {
          throw new Error('Failed to log out');
        }

        // Redirect to the home page after logout
        window.location.href = '/';
      } catch (error) {
        console.error('Error during logout:', error);
      }
    };

    handleLogout();
  }, []);

  return <div>Loading...</div>; // You can customize this component as needed
};

export default Logout;
