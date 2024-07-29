import React from 'react';
import ReactDOM from 'react-dom/client'; // Updated for React 18
import RoutesConfig from './screens/RouteConfig';
import { BrowserRouter } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';


// Update for React 18
const root = ReactDOM.createRoot(document.getElementById('root'));

root.render(
<BrowserRouter>
  <RoutesConfig />
</BrowserRouter>
);
