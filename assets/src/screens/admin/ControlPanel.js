import React, { useEffect, useState } from 'react';

const ControlPanel = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchUsers = async () => {
            try {
                // Correct the fetch URL if needed
                const response = await fetch('/api/control_panel');
                if (!response.ok) {
                    throw new Error('Failed to fetch users');
                }
                const data = await response.json();
                console.log('Fetched data:', data); // Debugging: log the fetched data
                setUsers(data.users || []); // Adjust according to your API response structure
            } catch (error) {
                console.error('Error fetching users:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchUsers();
    }, []);

    const handleProfileClick = (idprofile) => {
        try {
            localStorage.setItem('idprofile', idprofile);
            window.location.href = '/profile';
        } catch (error) {
            console.error('Error handling profile click:', error);
        }
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    const handleView = (userId) => {
        // Handle view action
        console.log(`View details for user ID: ${userId}`);
    };

    const handleReport = (userId) => {
        // Handle report action
        console.log(`Report user ID: ${userId}`);
    };

    const handleDeactivate = (userId) => {
        // Handle deactivate action
        console.log(`Deactivate user ID: ${userId}`);
    };

    return (
        <div className="control-panel">
            <h1>Control Panel</h1>
            <div className="buttons">
                <form action="/logout" method="POST">
                    <button type="submit" className="btn btn-danger">Logout</button>
                </form>
                <form action="/" method="GET">
                    <button type="submit" className="btn btn-success">Home</button>
                </form>
            </div>
            <div className="user-list mt-4">
                {users.length > 0 ? (
                    users.map((user) => (
                        <div key={user.id} className="user-item mb-3">
                            <p><strong>Name:</strong> {user.Firstname}</p>
                            <p><strong>Email:</strong> {user.Email}</p>
                            <button className="btn btn-primary me-2" onClick={() => handleView(user.id)}>View</button>
                            <button className="btn btn-warning me-2" onClick={() => handleReport(user.id)}>Report</button>
                            <button className="btn btn-danger" onClick={() => handleDeactivate(user.id)}>Deactivate</button>
                        </div>
                    ))
                ) : (
                    <p>No users found.</p>
                )}
            </div>
        </div>
    );
};

export default ControlPanel;
