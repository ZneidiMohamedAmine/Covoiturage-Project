import React from 'react';
import { Route, Routes } from 'react-router-dom';
import Home from './user/Home';
import ControlPanel from './admin/ControlPanel';
import Login from './anonymous/login';
import Profile from './user/profile';
import Register from './anonymous/register';
import Logout from './user/logout';
import JoinedTrip from './user/JoinedTrip';


const RoutesConfig = () => (
  <Routes>
    <Route path="/" element={<Home />} />
    <Route path="/control-panel" element={<ControlPanel />} />
    <Route path="/login" element={<Login />} />
    <Route path="/profile" element={<Profile />} />
    <Route path="/register" element={<Register />} />
    <Route path="/logout" element={<Logout />} />
    <Route path="/yourtrip" element={<JoinedTrip />} />
    
    {/* Add other routes as needed */}
  </Routes>
);

export default RoutesConfig;
