import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom'; // Added useNavigate

const Register = () => {
  const [formData, setFormData] = useState({
    firstname: '',
    lastname: '',
    email: '',
    birthdate: '',
    gender: 'male',
    driver_license: false,
    cin: '',
    address: '',
    password: ''
  });

  const [error, setError] = useState([]);
  const [loading, setLoading] = useState(true); // Added
  const navigate = useNavigate(); // Added

  useEffect(() => { // Added useEffect
    const checkLoggedIn = () => {
      try {
        const isAuthenticated = !!localStorage.getItem('jwtToken');
        if (isAuthenticated) {
          navigate('/'); // Redirect to home if already logged in
        } else {
          setLoading(false);
        }
      } catch (error) {
        setError([error.message]);
        setLoading(false);
      }
    };

    checkLoggedIn();
  }, [navigate]);

  const handleInputChange = (event) => {
    const { name, value, type, checked } = event.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleFormSubmit = async (event) => {
    event.preventDefault();
    try {
      const response = await fetch('/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      const responseData = await response.json();

      if (!response.ok) {
        setError(responseData.errors || ['Failed to register']);
        throw new Error('Network response was not ok: ' + JSON.stringify(responseData));
      }

      const token = responseData.jwt;
      

      // Store the token in localStorage
      localStorage.setItem('jwtToken', token);

      await fetch('/api/profile', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      

      navigate('/profile');
    } catch (error) {
      console.error(error.message);
    }
  };

  if (loading) return <div>Loading...</div>; // Added
  if (error.length > 0 && loading === false) return <div>Error: {error.join(', ')}</div>; // Added

  return (
    <div className="container">
      <div className="row justify-content-center">
        <div className="col-md-8">
          <div className="card mt-5">
            <div className="card-header">
              <h2 className="text-center">User Registration</h2>
            </div>
            <div className="card-body">
              <form id="register-form" onSubmit={handleFormSubmit}>
                {error.length > 0 && (
                  <div className="alert alert-danger">
                    <ul>
                      {error.map((message, index) => (
                        <li key={index}>{message}</li>
                      ))}
                    </ul>
                  </div>
                )}
                <div className="form-group">
                  <label htmlFor="firstname">First Name:</label>
                  <input
                    type="text"
                    className="form-control"
                    id="firstname"
                    name="firstname"
                    value={formData.firstname}
                    onChange={handleInputChange}
                    required
                  />
                </div>
                <div className="form-group">
                  <label htmlFor="lastname">Last Name:</label>
                  <input
                    type="text"
                    className="form-control"
                    id="lastname"
                    name="lastname"
                    value={formData.lastname}
                    onChange={handleInputChange}
                    required
                  />
                </div>
                <div className="form-group">
                  <label htmlFor="email">Email:</label>
                  <input
                    type="email"
                    className="form-control"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleInputChange}
                    required
                  />
                </div>
                <div className="form-group">
                  <label htmlFor="birthdate">Birthdate:</label>
                  <input
                    type="date"
                    className="form-control"
                    id="birthdate"
                    name="birthdate"
                    value={formData.birthdate}
                    onChange={handleInputChange}
                    required
                  />
                </div>
                <div className="form-group">
                  <label htmlFor="gender">Gender:</label>
                  <select
                    className="form-control"
                    id="gender"
                    name="gender"
                    value={formData.gender}
                    onChange={handleInputChange}
                    required
                  >
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div className="form-check">
                  <input
                    type="checkbox"
                    className="form-check-input"
                    id="driver_license"
                    name="driver_license"
                    checked={formData.driver_license}
                    onChange={handleInputChange}
                  />
                  <label className="form-check-label" htmlFor="driver_license">
                    Driver's License
                  </label>
                </div>
                <div className="form-group">
                  <label htmlFor="cin">CIN:</label>
                  <input
                    type="text"
                    className="form-control"
                    id="cin"
                    name="cin"
                    value={formData.cin}
                    onChange={handleInputChange}
                    required
                  />
                </div>
                <div className="form-group">
                  <label htmlFor="address">Address:</label>
                  <textarea
                    className="form-control"
                    id="address"
                    name="address"
                    value={formData.address}
                    onChange={handleInputChange}
                    required
                  ></textarea>
                </div>
                <div className="form-group">
                  <label htmlFor="password">Password:</label>
                  <input
                    type="password"
                    className="form-control"
                    id="password"
                    name="password"
                    value={formData.password}
                    onChange={handleInputChange}
                    required
                  />
                </div>
                <button type="submit" className="btn btn-primary">
                  Register
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Register;
