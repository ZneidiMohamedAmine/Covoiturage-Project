import React from "react";
import { Route, Routes } from "react-router-dom";

// Anonymous
import Home from "./anonymous/Home";
import Login from "./anonymous/Login";
import Register from "./anonymous/Register";
import NotFound from "./anonymous/NotFound";

// User
import UserProfile from "./user/Profile";
import UserProfileModify from "./user/ProfileModify";
import UserDesactivate from "./user/Desactivate";
import Logout from "./user/Logout";

// Admin
import AdminControlPanel from "./admin/ControlPanel";

// Trajet Management
import TrajetCreate from "./trajet/TrajetCreate";
import TrajetModify from "./trajet/TrajetModify";
import TrajetDelete from "./trajet/TrajetDelete";

// Reservation Management
import ReservationCreate from "./reservation/ReservationCreate";
import ReservationDelete from "./reservation/ReservationDelete";

export default function RouteConfig() {
    return (
        <Routes>
            {/* Anonymous */}
            <Route path="/" element={<Home />} />
            <Route path="/register" element={<Register />} />
            <Route path="/login" element={<Login />} />

            {/* User */}
            <Route path="/user/profile" element={<UserProfile />} />
            <Route path="/user/profile/modify" element={<UserProfileModify />} />
            <Route path="/user/profile/desactivate" element={<UserDesactivate />} />
            <Route path="/logout" element={<Logout />} />

            {/* Admin */}
            <Route path="/admin/control-panel" element={<AdminControlPanel />} />

            {/* Trajet Management */}
            <Route path="/trajet/create" element={<TrajetCreate />} />
            <Route path="/trajet/modify" element={<TrajetModify />} />
            <Route path="/trajet/delete" element={<TrajetDelete />} />

            {/* Reservation Management */}
            <Route path="/reservation/create" element={<ReservationCreate />} />
            <Route path="/reservation/delete" element={<ReservationDelete />} />

            {/* Common */}
            <Route path="*" element={<NotFound />} />
        </Routes>
    );
}
