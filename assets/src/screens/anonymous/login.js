import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const Login = () => {
    const [formData, setFormData] = useState({
        email: '',
        password: ''
    });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const navigate = useNavigate();

    useEffect(() => {
        const checkLoggedIn = () => {
            try {
                const isAuthenticated = !!localStorage.getItem('jwtToken');
                if (isAuthenticated) {
                    navigate('/'); // Redirect to home if already logged in
                } else {
                    setLoading(false);
                }
            } catch (error) {
                setError(error.message);
                setLoading(false);
            }
        };

        checkLoggedIn();
    }, [navigate]);

    const handleInputChange = (event) => {
        const { name, value } = event.target;
        setFormData(prevData => ({ ...prevData, [name]: value }));
    };

    const handleFormSubmit = async (event) => {
        event.preventDefault();
        setLoading(true);
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                throw new Error('Failed to login');
            }

            const responseData = await response.json();
            const token = responseData.jwt;

            // Store the token in localStorage
            localStorage.setItem('jwtToken', token);

            await fetch('/api/profile', {
                method: 'POST',
                headers: {
                  'Authorization': `Bearer ${token}`
                }
              })
        
                    

            // Redirect to profile page
            navigate('/profile');
        } catch (error) {
            setError(error.message);
            setLoading(false);
        }
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    return (
        <div className="login-form-container">
            <h2>Login</h2>
            {error && <div className="error">{error}</div>}
            <form onSubmit={handleFormSubmit}>
                <label htmlFor="email">Email:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleInputChange}
                    required
                />
                <label htmlFor="password">Password:</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    value={formData.password}
                    onChange={handleInputChange}
                    required
                />
                <button type="submit" className="btn btn-primary">Login</button>
            </form>

            <form action="/register" method="POST">
                            <button type="submit" className="btn btn-success">Register</button>
                        </form>
        </div>
    );
};

export default Login;
