import React from 'react';

function Header() {
  // Simulating user authentication status
  const isAuthenticated = true; // Replace with actual authentication logic

  return (
    <div className="header">
      {isAuthenticated ? (
        // User is authenticated
        <form action="/app_logout" method="POST">
          <button type="submit">Logout</button>
        </form>
      ) : (
        // User is not authenticated
        <>
          <form action="/app_login" method="POST">
            <button type="submit">Login</button>
          </form>
          <form action="/app_register" method="POST">
            <button type="submit">Register</button>
          </form>
        </>
      )}
    </div>
  );
}

function SearchContainer() {
  return (
    <div className="search-container">
      <form>
        <input type="text" name="query" placeholder="Search..." />
        <button type="submit">Search</button>
      </form>
    </div>
  );
}

function App() {
  return (
    <body>
      <Header />
      <SearchContainer />
    </body>
  );
}

export default App;
