// RouteConfig.js
import React from 'react';
import { Route, Routes } from 'react-router-dom';
import Home from './anonymous/Home';

// Import other components as needed

const RoutesConfig = () => (
  <Routes>
    <Route path="/" element={<Home />} />
    
    {/* Add other routes as needed */}
  </Routes>
);

export default RoutesConfig;
