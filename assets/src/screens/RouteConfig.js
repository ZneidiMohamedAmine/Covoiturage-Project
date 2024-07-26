import React from 'react';
import { Route, Routes } from 'react-router-dom';
import Home from './anonymous/Home';
import ControlPanel from './admin/ControlPanel';
import Login from './anonymous/login';
import Profile from './user/profile';
import Register from './anonymous/register';

const RoutesConfig = () => (
  <Routes>
    <Route path="/" element={<Home />} />
    <Route path="/control-panel" element={<ControlPanel />} />
    <Route path="/login" element={<Login />} />
    <Route path="/profile" element={<Profile />} />
    <Route path="/register" element={<Register />} />
    
    {/* Add other routes as needed */}
  </Routes>
);

export default RoutesConfig;
